		</div>
		<div class="footer">
			<a href="about">About</a> - 
			<?php printf("%dKB used @ %1.3f secs", memory_get_usage(false) / 1024, microtime(true) - $start) ?>
		</div>
		<script type="text/javascript" src="assets/base.js"></script>
	</body>
</html>