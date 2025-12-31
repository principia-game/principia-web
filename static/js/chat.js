// helper to format unix timestamp (seconds or milliseconds) to HH:MM local time
function formatTime(unixTs) {
	let t = Number(unixTs) || 0;
	// if ts looks like seconds (around 10 digits), convert to ms
	if (t > 0 && t < 1e11) t *= 1000;

	const d = new Date(t);
	const hh = String(d.getHours()).padStart(2, '0');
	const mm = String(d.getMinutes()).padStart(2, '0');

	return `${hh}:${mm}`;
}

const MIN_DELAY = 500;      // ms
const MAX_DELAY = 30000;    // ms
const BACKOFF_FACTOR = 1.5;

class API {
	static async fetchMessages(lastId) {
		return fetch('/chat/fetch?last_id=' + lastId)
			.then(res => {
				if (!res.ok) throw new Error('network');
				return res.json();
			});
	}

	static async sendMessage(message) {
		return fetch('/chat/send', {
			method: 'POST',
			headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			body: 'message=' + encodeURIComponent(message)
		}).then(res => {
			if (!res.ok) throw new Error('network');
			return res;
		});
	}
}

class AudioNotify {
	audio;
	shouldPlay;

	constructor() {
		this.audio = new Audio('/assets/message.ogg');
		this.audio.preload = 'auto';
		this.audio.volume = 0.85;

		let shouldPlay = localStorage.getItem('chatPlaySound');
		this.shouldPlay = shouldPlay === null ? true : (shouldPlay == 1);

		const checkbox = document.getElementById('soundToggle');
		checkbox.checked = shouldPlay === null ? true : (shouldPlay == 1);
		checkbox.addEventListener('change', () => {
			this.shouldPlay = checkbox.checked;
			localStorage.setItem('chatPlaySound', this.shouldPlay ? 1 : 0);
		});
	}

	play() {
		if (this.shouldPlay)
			this.audio.play().catch(() => { /* ignore play errors */ });
	}
}

class Chatbox {
	el = document.getElementById('messages');

	constructor() {

	}

	isScrolledToBottom(tolerance = 10) {
		return (this.el.scrollHeight - this.el.scrollTop - this.el.clientHeight) <= tolerance;
	}

	scrollToBottom() {
		this.el.scrollTop = this.el.scrollHeight;
	}

	addMessage(html) {
		const el = document.createElement('div');

		el.className = 'comment';
		el.innerHTML = html;
		this.el.appendChild(el);
	}
}

class Chat {
	userId = Number(document.getElementById('chat-info').dataset.userid);

	audioNotify = new AudioNotify();
	chatbox = new Chatbox();

	init = true;
	lastMessageId = 0;
	currentDelay = MIN_DELAY;
	pollTimer = null;

	constructor() {
		const inputField = document.getElementById('chatInput');

		document.getElementById('sendBtn')
			.addEventListener('click', () => this.sendMessage(inputField));
		inputField.addEventListener('keydown', event => {
			if (event.key === 'Enter' && !event.shiftKey)
				this.sendMessage(inputField);
		});

		// start polling
		this.fetchMessages();
	}

	resetDelay() {
		this.currentDelay = MIN_DELAY;
	}

	increaseDelay() {
		this.currentDelay = Math.min(this.currentDelay * BACKOFF_FACTOR, MAX_DELAY);
	}

	scheduleNextPoll(delay) {
		const jitter = 0.8 + Math.random() * 0.4;
		this.pollTimer = setTimeout(() => this.fetchMessages(), delay * jitter);
		console.log(`Next poll in ${(delay * jitter).toFixed(0)} ms`);
	}

	formatMessage(msg) {
		const formattedTime = formatTime(msg.time);

		let username = msg.user.name;
		if (msg.user.customcolor)
			username = `<span style="color:#${msg.user.customcolor}">${msg.user.name}</span>`;

		let userlink = `<a class="user" href="/user/${msg.user.id}"><span class="t_user">${username}</span></a>`;

		return `
			<span class="mono">[${formattedTime}]</span>
			${userlink}: ${msg.message}`;
	}

	fetchMessages() {
		const wasAtBottom = this.chatbox.isScrolledToBottom();

		API.fetchMessages(this.lastMessageId).then(messages => {
			if (messages.length == 0) {
				this.increaseDelay();
				this.scheduleNextPoll(this.currentDelay);
				return;
			}

			// play sound for new messages (but not on initial load)
			let shouldPlay = false;

			messages.forEach(msg => {
				this.chatbox.addMessage(this.formatMessage(msg));
				this.lastMessageId = msg.id;

				if (msg.user.id !== this.userId)
					shouldPlay = true;
			});

			if (!this.init && shouldPlay)
				this.audioNotify.play();

			// only auto-scroll if user was at bottom or this is the initial load
			if (wasAtBottom || this.init)
				this.chatbox.scrollToBottom();

			this.init = false;

			this.resetDelay();
			this.scheduleNextPoll(this.currentDelay);
		})
		.catch(() => {
			// on error, clear this.initialLoad to avoid repeated forced scrolling later
			if (this.init) this.init = false;

			this.increaseDelay();
			this.scheduleNextPoll(this.currentDelay);
		});
	}

	sendMessage(inputField) {
		const message = inputField.value.trim();
		if (!message) return;

		API.sendMessage(message).then(() => {
			inputField.value = '';

			this.resetDelay();
			if (this.pollTimer)
				clearTimeout(this.pollTimer);

			this.fetchMessages();
		});
	}
}

const chat = new Chat();
