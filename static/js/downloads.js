const tabs = document.querySelectorAll(".platform-tabs .tab");
const panels = document.querySelectorAll(".dl-panel");

function selectPlatform(platform, updateHash) {
    tabs.forEach(function (tab) {
        const selected = tab.dataset.platform === platform;

        tab.classList.toggle("active", selected);
        tab.setAttribute("aria-selected", selected ? "true" : "false");
    });

    panels.forEach(function (panel) {
        const selected = panel.dataset.platformPanel === platform;

        panel.hidden = !selected;
        panel.classList.toggle("active", selected);
    });

    if (updateHash && history.replaceState)
        history.replaceState(null, "", "#" + platform);
}

tabs.forEach(function (tab) {
    tab.addEventListener("click", function () {
        selectPlatform(tab.dataset.platform, true);
    });
});

function getDefaultPlatform() {
    const ua = navigator.userAgent.toLowerCase();

    if (/cros/.test(ua))
        return "web";

    if (/android/.test(ua))
        return "android";

    if (/windows/.test(ua))
        return "windows";

    if (/linux/.test(ua))
        return "linux";

    if (/macintosh|mac os x/.test(ua))
        return "macos";

    // Windows tab is the first tab and is default for now
    return "windows";
}

const hash = window.location.hash.substring(1);

if (hash && Array.from(tabs).some(function (tab) { return tab.dataset.platform === hash; }))
    selectPlatform(hash, false);
else
    selectPlatform(getDefaultPlatform(), false);
