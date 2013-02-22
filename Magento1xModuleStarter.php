<?php
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.

// This script uses the process described at 
// http://coding.smashingmagazine.com/2012/03/01/basics-creating-magento-module/
// to start Magento 1.x module creation in <MAGE BASEDIR>/app/code/local
// Make sure you have write permissions to the Magento base directory.

// start
// check arguments
if (sizeof($argv)!=4) 
	exit("usage: " . $argv[0] . " <mage basedir> <namespace> <mod_name>\n");
try {
	$mod=new MagentoModuleStarter($argv[1], $argv[2], $argv[3]);
	$mod->structureModDir();
	$mod->cfgModule();
	$mod->activateModule();
	print("Log into the Magento admin panel and navigate to " 
		. "System > Configuration > Advanced > Advanced and view "
		. "the Disable Modules Output listing. You should see "
		. "this module listed as enabled. Now follows the steps in "
		. "the above link to customize the module for your needs.\n");
}
catch (Exception $ex) {
	print($ex->getMessage() . "\n");
}

Class MagentoModuleStarter 
{
	private $mage_basedir="";
	private $mod_ns="";
	private $mod_nm="";
	private $mage_localdir="";
	private $mage_moduledir="";

	public function __construct($basedir, $ns, $nm) 
	{
		$this->mage_basedir=$basedir;
		$this->mod_ns=$ns;
		$this->mod_nm=$nm;

		// basic check that the directory has Mage source
		if (is_dir($this->mage_basedir . '/app/code/core')==FALSE)
			throw new Exception($this->mage_basedir 
				. " does not appear to be a Magento install\n");
	}

	public function structureModDir() 
	{
		// create app/code/local if not exists
		$this->mage_localdir=$this->mage_basedir . '/app/code/local';
		if (is_dir($this->mage_localdir)==FALSE) {
			$local_created=mkdir($this->mage_localdir, $mode=0755);
			if ($local_created==FALSE)
				exit('unable to create ' 
					. $this->mage_localdir . "\n");
		}

		// create dirs for namespace and module
		$this->mage_moduledir=
			$this->mage_localdir . '/' 
				. $this->mod_ns . '/' . $this->mod_nm;
		$ns_created=mkdir($this->mage_moduledir, 0755, TRUE);
		if ($ns_created==FALSE) {
			exit('unable to create ' 
				. $this->mage_moduledir . "\n");
		}

		// create sub-dirs within module dir
		$ns_created=mkdir($this->mage_moduledir . '/Block', 0755, TRUE);
		if ($ns_created==FALSE) exit("failed to create Block dir\n");
		$ns_created=mkdir($this->mage_moduledir . '/controllers', 0755, TRUE);
		if ($ns_created==FALSE) exit("failed to create controllers dir\n");
		$ns_created=mkdir($this->mage_moduledir . '/Helper', 0755, TRUE);
		if ($ns_created==FALSE) exit("failed to create Helper dir\n");
		$ns_created=mkdir($this->mage_moduledir . '/Model', 0755, TRUE);
		if ($ns_created==FALSE) exit("failed to create Model dir\n");
		$ns_created=mkdir($this->mage_moduledir . '/sql', 0755, TRUE);
		if ($ns_created==FALSE) exit("failed to create sql dir\n");
	}

	// create <MODULE_BASEDIR>/etc/config.xml
	public function cfgModule() 
	{
		$etc_created=mkdir($this->mage_moduledir . '/etc', 0755, TRUE);
		if ($etc_created==FALSE)
			exit ("unable to create etc sub-dir\n");
		$fh_cfgxml=fopen($this->mage_moduledir . '/etc/config.xml'
			, 'w');
		if ($fh_cfgxml==FALSE)
			exit ("unable to create config.xml\n");
		$cfgxml=$this->getConfigXml();
		fwrite($fh_cfgxml, $cfgxml);
		fclose($fh_cfgxml);
	}

	// create <MAGE_APPDIR>/etc/modules/Namespace_Module.xml
	public function activateModule()
	{
		$fh_modxml=fopen($this->mage_basedir 
			. '/app/etc/modules/' . $this->mod_ns . '_'
			. $this->mod_nm . '.xml', 'w');
		if ($fh_modxml==FALSE)
			exit("unable to activate module\n");
		$modxml=$this->getModuleCfgXml();
		fwrite($fh_modxml, $modxml);
		fclose($fh_modxml);
	}

	private function getConfigXml()
	{
		$out = '<?xml version="1.0" encoding="UTF-8"?>';
		$out = $out . "\n" . '<config>';
		$out = $out . "\n\t" . '<modules>';
		$out = $out . "\n\t\t" . '<' . $this->mod_ns 
			. '_' . $this->mod_nm . '>';
		$out = $out . "\n\t\t\t" . '<version>1.0.0</version>'; 
		$out = $out . "\n\t\t" . '</' . $this->mod_ns 
			. '_' . $this->mod_nm . '>';
		$out = $out . "\n\t" . '</modules>';
		$out = $out . "\n" . '</config>';
		
		return $out;
	}

	private function getModuleCfgXml() 
	{
		$out = '<?xml version="1.0" encoding="UTF-8"?>';
		$out = $out . "\n" . '<config>';
		$out = $out . "\n\t" . '<modules>';
		$out = $out . "\n\t\t" . '<' . $this->mod_ns 
			. '_' . $this->mod_nm . '>';
		$out = $out . "\n\t\t\t" . '<active>true</active>'; 
		$out = $out . "\n\t\t\t" . '<codePool>local</codePool>'; 
		$out = $out . "\n\t\t" . '</' . $this->mod_ns 
			. '_' . $this->mod_nm . '>';
		$out = $out . "\n\t" . '</modules>';
		$out = $out . "\n" . '</config>';
		
		return $out;
	}
}
?>
