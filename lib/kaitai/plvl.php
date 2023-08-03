<?php
// This is a generated file! Please edit source .ksy file and use kaitai-struct-compiler to rebuild

class Plvl extends \Kaitai\Struct\Struct {
	public function __construct(\Kaitai\Struct\Stream $_io, \Kaitai\Struct\Struct $_parent = null, \Plvl $_root = null) {
		parent::__construct($_io, $_parent, $_root);
		$this->_read();
	}

	private function _read() {
		$this->_m_version = $this->_io->readU1();
		$this->_m_type = $this->_io->readU1();
		$this->_m_communityId = $this->_io->readU4le();
		if ($this->version() >= 28) {
			$this->_m_autosaveId = $this->_io->readU4le();
		}
		$this->_m_revision = $this->_io->readU4le();
		$this->_m_parentId = $this->_io->readU4le();
		$this->_m_nameSize = $this->_io->readU1();
		$this->_m_descrSize = $this->_io->readU2le();
		$this->_m_allowDerivatives = $this->_io->readU1();
		if ($this->version() >= 3) {
			$this->_m_visibility = $this->_io->readU1();
		}
		if ($this->version() >= 7) {
			$this->_m_parentRevision = $this->_io->readU4le();
		}
		if ($this->version() >= 7) {
			$this->_m_pauseOnFinish = $this->_io->readU1();
		}
		if ($this->version() >= 7) {
			$this->_m_showScore = $this->_io->readU1();
		}
		$this->_m_bg = $this->_io->readU1();
		if ($this->version() >= 28) {
			$this->_m_bgColor = $this->_io->readU4le();
		}
		$this->_m_sizeX = $this->_io->readU2le();
		$this->_m_sizeY = $this->_io->readU2le();
		if ($this->version() >= 12) {
			$this->_m_sizeX2 = $this->_io->readU2le();
		}
		if ($this->version() >= 12) {
			$this->_m_sizeY2 = $this->_io->readU2le();
		}
		$this->_m_velocityIterations = $this->_io->readU1();
		$this->_m_positionIterations = $this->_io->readU1();
		$this->_m_finalScore = $this->_io->readU4le();
		$this->_m_sandboxCamX = $this->_io->readF4le();
		$this->_m_sandboxCamY = $this->_io->readF4le();
		$this->_m_sandboxCamZoom = $this->_io->readF4le();
		if ($this->version() >= 3) {
			$this->_m_gravityX = $this->_io->readF4le();
		}
		if ($this->version() >= 3) {
			$this->_m_gravityY = $this->_io->readF4le();
		}
		if ($this->version() >= 13) {
			$this->_m_boundsX1 = $this->_io->readF4le();
		}
		if ($this->version() >= 13) {
			$this->_m_boundsY1 = $this->_io->readF4le();
		}
		if ($this->version() >= 13) {
			$this->_m_boundsX2 = $this->_io->readF4le();
		}
		if ($this->version() >= 13) {
			$this->_m_boundsY2 = $this->_io->readF4le();
		}
		if ($this->version() >= 9) {
			$this->_m_flags = $this->_io->readU8le();
		}
		if ($this->version() >= 26) {
			$this->_m_prismaticTolerance = $this->_io->readF4le();
		}
		if ($this->version() >= 26) {
			$this->_m_pivotTolerance = $this->_io->readF4le();
		}
		if ($this->version() >= 28) {
			$this->_m_seed = $this->_io->readU8le();
		}
		if ($this->version() >= 28) {
			$this->_m_adventureId = $this->_io->readU4le();
		}
		if ($this->version() >= 28) {
			$this->_m_linearDamping = $this->_io->readF4le();
		}
		if ($this->version() >= 28) {
			$this->_m_angularDamping = $this->_io->readF4le();
		}
		if ($this->version() >= 28) {
			$this->_m_jointFriction = $this->_io->readF4le();
		}
		if ($this->version() >= 28) {
			$this->_m_bodyAbsorbTime = $this->_io->readF4le();
		}
		if ($this->version() >= 28) {
			$this->_m_respawnCooldown = $this->_io->readF4le();
		}
		if ($this->version() >= 28) {
			$this->_m_compressionBufSize = $this->_io->readU8le();
		}
		$this->_m_name = \Kaitai\Struct\Stream::bytesToStr($this->_io->readBytes($this->nameSize()), "UTF-8");
		if ($this->version() >= 6) {
			$this->_m_levelThumbnail = $this->_io->readBytes((128 * 128));
		}
		$this->_m_descr = \Kaitai\Struct\Stream::bytesToStr($this->_io->readBytes($this->descrSize()), "UTF-8");
		if ($this->version() < 28) {
			$this->_m_groupCountPre28 = $this->_io->readU2le();
		}
		if ($this->version() < 28) {
			$this->_m_entityCountPre28 = $this->_io->readU2le();
		}
		if ($this->version() < 28) {
			$this->_m_connectionCountPre28 = $this->_io->readU2le();
		}
		if ($this->version() < 28) {
			$this->_m_cableCountPre28 = $this->_io->readU2le();
		}
		if ($this->version() >= 28) {
			$this->_m_groupCount = $this->_io->readU4le();
		}
		if ($this->version() >= 28) {
			$this->_m_entityCount = $this->_io->readU4le();
		}
		if ($this->version() >= 28) {
			$this->_m_connectionCount = $this->_io->readU4le();
		}
		if ($this->version() >= 28) {
			$this->_m_cableCount = $this->_io->readU4le();
		}
		if ($this->version() >= 28) {
			$this->_m_chunkCount = $this->_io->readU4le();
		}
		if ($this->version() >= 28) {
			$this->_m_stateSize = $this->_io->readU4le();
		}
		if ($this->version() >= 28) {
			$this->_m_gencount = $this->_io->readU4le();
		}
		$this->_m_levelBuffer = $this->_io->readBytesFull();
	}
	protected $_m_version;
	protected $_m_type;
	protected $_m_communityId;
	protected $_m_autosaveId;
	protected $_m_revision;
	protected $_m_parentId;
	protected $_m_nameSize;
	protected $_m_descrSize;
	protected $_m_allowDerivatives;
	protected $_m_visibility;
	protected $_m_parentRevision;
	protected $_m_pauseOnFinish;
	protected $_m_showScore;
	protected $_m_bg;
	protected $_m_bgColor;
	protected $_m_sizeX;
	protected $_m_sizeY;
	protected $_m_sizeX2;
	protected $_m_sizeY2;
	protected $_m_velocityIterations;
	protected $_m_positionIterations;
	protected $_m_finalScore;
	protected $_m_sandboxCamX;
	protected $_m_sandboxCamY;
	protected $_m_sandboxCamZoom;
	protected $_m_gravityX;
	protected $_m_gravityY;
	protected $_m_boundsX1;
	protected $_m_boundsY1;
	protected $_m_boundsX2;
	protected $_m_boundsY2;
	protected $_m_flags;
	protected $_m_prismaticTolerance;
	protected $_m_pivotTolerance;
	protected $_m_seed;
	protected $_m_adventureId;
	protected $_m_linearDamping;
	protected $_m_angularDamping;
	protected $_m_jointFriction;
	protected $_m_bodyAbsorbTime;
	protected $_m_respawnCooldown;
	protected $_m_compressionBufSize;
	protected $_m_name;
	protected $_m_levelThumbnail;
	protected $_m_descr;
	protected $_m_groupCountPre28;
	protected $_m_entityCountPre28;
	protected $_m_connectionCountPre28;
	protected $_m_cableCountPre28;
	protected $_m_groupCount;
	protected $_m_entityCount;
	protected $_m_connectionCount;
	protected $_m_cableCount;
	protected $_m_chunkCount;
	protected $_m_stateSize;
	protected $_m_gencount;
	protected $_m_levelBuffer;
	public function version() { return $this->_m_version; }
	public function type() { return $this->_m_type; }
	public function communityId() { return $this->_m_communityId; }
	public function autosaveId() { return $this->_m_autosaveId; }
	public function revision() { return $this->_m_revision; }

	/**
	 * Level ID of parent level (for derivatives)
	 */
	public function parentId() { return $this->_m_parentId; }
	public function nameSize() { return $this->_m_nameSize; }
	public function descrSize() { return $this->_m_descrSize; }
	public function allowDerivatives() { return $this->_m_allowDerivatives; }
	public function visibility() { return $this->_m_visibility; }
	public function parentRevision() { return $this->_m_parentRevision; }
	public function pauseOnFinish() { return $this->_m_pauseOnFinish; }
	public function showScore() { return $this->_m_showScore; }
	public function bg() { return $this->_m_bg; }

	/**
	 * Assumedly RGBA, needs testing though
	 */
	public function bgColor() { return $this->_m_bgColor; }
	public function sizeX() { return $this->_m_sizeX; }
	public function sizeY() { return $this->_m_sizeY; }
	public function sizeX2() { return $this->_m_sizeX2; }
	public function sizeY2() { return $this->_m_sizeY2; }
	public function velocityIterations() { return $this->_m_velocityIterations; }
	public function positionIterations() { return $this->_m_positionIterations; }
	public function finalScore() { return $this->_m_finalScore; }
	public function sandboxCamX() { return $this->_m_sandboxCamX; }
	public function sandboxCamY() { return $this->_m_sandboxCamY; }
	public function sandboxCamZoom() { return $this->_m_sandboxCamZoom; }
	public function gravityX() { return $this->_m_gravityX; }
	public function gravityY() { return $this->_m_gravityY; }
	public function boundsX1() { return $this->_m_boundsX1; }
	public function boundsY1() { return $this->_m_boundsY1; }
	public function boundsX2() { return $this->_m_boundsX2; }
	public function boundsY2() { return $this->_m_boundsY2; }

	/**
	 * TODO
	 */
	public function flags() { return $this->_m_flags; }
	public function prismaticTolerance() { return $this->_m_prismaticTolerance; }
	public function pivotTolerance() { return $this->_m_pivotTolerance; }
	public function seed() { return $this->_m_seed; }
	public function adventureId() { return $this->_m_adventureId; }
	public function linearDamping() { return $this->_m_linearDamping; }
	public function angularDamping() { return $this->_m_angularDamping; }
	public function jointFriction() { return $this->_m_jointFriction; }
	public function bodyAbsorbTime() { return $this->_m_bodyAbsorbTime; }
	public function respawnCooldown() { return $this->_m_respawnCooldown; }
	public function compressionBufSize() { return $this->_m_compressionBufSize; }
	public function name() { return $this->_m_name; }

	/**
	 * A 128x128 8-bit grayscale bitmap, which is a screenshot of the level where it last was saved. It is used for package thumbnails.
	 */
	public function levelThumbnail() { return $this->_m_levelThumbnail; }
	public function descr() { return $this->_m_descr; }
	public function groupCountPre28() { return $this->_m_groupCountPre28; }
	public function entityCountPre28() { return $this->_m_entityCountPre28; }
	public function connectionCountPre28() { return $this->_m_connectionCountPre28; }
	public function cableCountPre28() { return $this->_m_cableCountPre28; }
	public function groupCount() { return $this->_m_groupCount; }
	public function entityCount() { return $this->_m_entityCount; }
	public function connectionCount() { return $this->_m_connectionCount; }
	public function cableCount() { return $this->_m_cableCount; }
	public function chunkCount() { return $this->_m_chunkCount; }
	public function stateSize() { return $this->_m_stateSize; }
	public function gencount() { return $this->_m_gencount; }

	/**
	 * zlib compressed level buffer data. In earlier level versions (TODO: what versions?), the level buffer is uncompressed.
	 */
	public function levelBuffer() { return $this->_m_levelBuffer; }
}

class LevelVersion {
	const ANY = 0;
	const VERSION_BETA_1 = 1;
	const VERSION_BETA_2 = 2;
	const VERSION_BETA_3 = 3;
	const VERSION_BETA_4 = 4;
	const VERSION_BETA_5 = 5;
	const VERSION_BETA_6 = 6;
	const VERSION_BETA_7 = 7;
	const VERSION_BETA_8 = 8;
	const VERSION_BETA_9 = 9;
	const VERSION_BETA_10 = 10;
	const VERSION_BETA_11 = 11;
	const VERSION_BETA_12 = 12;
	const VERSION_BETA_13 = 13;
	const VERSION_BETA_14 = 14;
	const VERSION_1_0 = 15;
	const VERSION_1_1_6 = 16;
	const VERSION_1_1_7 = 17;
	const VERSION_1_2 = 18;
	const VERSION_1_2_1 = 19;
	const VERSION_1_2_2 = 20;
	const VERSION_1_2_3 = 21;
	const VERSION_1_2_4 = 22;
	const VERSION_1_3_0_1 = 23;
	const VERSION_1_3_0_2 = 24;
	const VERSION_1_3_0_3 = 25;
	const VERSION_1_4 = 26;
	const VERSION_1_4_0_2 = 27;
	const VERSION_1_5 = 28;
	const VERSION_1_5_1 = 29;
}

class LevelType {
	const PUZZLE = 0;
	const ADVENTURE = 1;
	const CUSTOM = 2;
}
