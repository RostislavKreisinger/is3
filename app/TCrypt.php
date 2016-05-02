<?php

namespace App;

/**
 * 
 */
class TCrypt
{

/**
 * error class
 */
public $errors=array();

/**
 * minimum passphrase lenght
 */
protected $minPassLen=3;
/**
 * size of IV
 * default 16
 */
private $tIVSize=16;
/**
 * crypting method
 * default AES-256-CBC
 */
private $tMethod='AES-256-CBC';
/**
 * day constant
 * default 365
 * used for certificates
 */
private $tDays=365;
/**
 * CSR
 */
private $tCsr;
/**
 * self signed certificate
 */
private $tCertificate;

private $tSalt='RGFD345F5S3H6ac5bGT87dsfg32GHJ567WAVBM0';
private $tRev=50;

private $tcrypt_id;
private $tcrypt_hash;
private $passphrase;
private $public=false;
private $private=false;
private $created;
private $modified;

function __construct()
{
  //$this->errors=new IcafError();
} // construct

/**
 * makes and returns unique IV
 * uses the lenght specified in $this->tIvSize
 * @param $ivSalt extra salt for IV uniqueness
 * @return String IV
 */
public function makeIV($ivSalt=null)
{
  $iv=mcrypt_create_iv($this->tIVSize, MCRYPT_RAND);
  $iv=md5($iv.$ivSalt);
  $iv=base64_encode($iv);
  $iv=substr($iv, 0, $this->tIVSize);
  return (string)$iv;
} // makeIV

/**
 * makes a pair of keys
 * also makes CSR and SSCertificate
 * sets $this->public, $this->private, $this->tKey, $this->tCsr, $this->tCertificate
 * @return Boolean true if ok, false if not
 */
public function makeKeyPair()
{
  $dn = array(
      "countryName" => "CZ",
      "stateOrProvinceName" => "Czech Republic",
      "localityName" => "Ostrava",
      "organizationName" => "Monkey Data",
      //"organizationalUnitName" => "",
      "commonName" => "MD",
      "emailAddress" => "info@monkeydata.cz"
  );
  $resource=@openssl_pkey_new();
  $csr=openssl_csr_new($dn, $resource);
  $sscert=openssl_csr_sign($csr, null, $resource, $this->tDays);
  openssl_csr_export($csr, $csrout);
  openssl_x509_export($sscert, $certout);
  openssl_pkey_export($resource, $private);
  $p=openssl_pkey_get_details($resource);
  $public=$p['key'];
  $this->private=$private;
  $this->public=$public;
  $this->hashPKey();
  $this->tCsr=$csrout;
  $this->tCertificate=$certout;
  return true;
} // makeKeyPair

/**
 * separates IV and data
 * modifies $dataEc
 * @param $dataEc IV+data string
 * @return String IV taken from $dataEc, false if error
 */
protected function separateIVData(&$dataEc)
{
  if (strlen($dataEc)<32)
  {
    $this->errors->addError('COM_ICAF_TCRYPT_INVALID_IVDATA');
    return false;
  }
  $iv=substr($dataEc, 0, $this->tIVSize);
	$dataEc=substr($dataEc, $this->tIVSize);
  return (string)$iv;
} // separateIVData

/**
 * combines IV and data
 * @param $data data string
 * @param $iv IV string
 * @return String IV+data  
 */
protected function combineIVData($data, $iv)
{
  return (string)$iv.$data;
} // combineIVData

/**
 * encrypts data using $passphrase
 * uses symmetric crypting
 * useful for data longer then 1024 bytes
 * @param $data data string to encrypt
 * @param $passphrase crypting passphrase
 * @param $iv IV previously made
 * @param $combineIVData if false do NOT combine IV and data - default true
 * @return String encrypted data, false if error
 */
public function encryptData($data, $passphrase=null, $iv=null, $combineIVData=true)
{
  if (!isset($data))
  {
    //$this->errors->addError('COM_ICAF_TCRYPT_DATA_NOT_SET');
    return false;
  }
  if (!isset($passphrase))
  {
    $passphrase=$this->passphrase;
  }
  elseif (!$this->checkPassComplexity($passphrase))
  {
    //$this->errors->addError('COM_ICAF_TCRYPT_PASSPHRASE_COMPLEXITY_ERROR');
    return false;
  }
  if (!isset($iv))
  {
    $iv=$this->makeIV();
  }
  elseif (strlen($iv)<$this->tIVSize)
  {
    //$this->errors->addError('COM_ICAF_TCRYPT_IV_SIZE_ERROR');
    return false;
  }
  else
  {
    $iv=substr($iv, 0, $this->tIVSize);
  }
  $encrypted=openssl_encrypt($data, $this->tMethod, $passphrase, false, $iv);
  if ($combineIVData) return $this->combineIVData($encrypted, $iv);
  return $encrypted;
} // encryptData

/**
 * decrypts data using $passphrase
 * uses symmetric crypting
 * useful for data longer then 1024 bytes
 * @param $dataEc data string to decrypt
 * @param $passphrase crypting passphrase
 * @param $iv IV previously made
 * @param $combinedIVData if false assumes that data are NOT combined with IV - default true (ignores param $iv)
 * @return String decrypted data
 * @return false if error
 */
public function decryptData($dataEc, $passphrase=null, $iv=null, $combinedIVData=true)
{
  if (!isset($dataEc))
  {
    //$this->errors->addError('COM_ICAF_TCRYPT_DATA_NOT_SET');
    return false;
  }
  if (!isset($passphrase))
  {
    $passphrase=$this->passphrase;
  }
  elseif (!$this->checkPassComplexity($passphrase))
  {
    //$this->errors->addError('COM_ICAF_TCRYPT_PASSPHRASE_COMPLEXITY_ERROR');
    return false;
  }
  if ($combinedIVData)
  { // dataEc=IV+Data
    $iv=$this->separateIVData($dataEc);
  }
  else
  { // dataEc=data
    if (strlen($iv)<$this->tIVSize)
    {
      //$this->errors->addError('COM_ICAF_TCRYPT_IV_SIZE_ERROR');
      return false;
    }
    else
    {
      $iv=substr($iv, 0, $this->tIVSize);
    }
  } 
  $decrypted=openssl_decrypt($dataEc, $this->tMethod, $passphrase, false, $iv);
  return $decrypted;
} // decryptData

/**
 * encrypts $passphrase using JCryptKey
 * uses asymmetric crypting
 * only for phrases lesser then 1023 bytes
 * useful for passphrase crypting, signing hashes, etc.
 * @param $passphrase phrase string to encrypt
 * @param $public if false encrypts using private key (signing data) - default true (public key usage)
 * @return String encrypted phrase, false if error
 */
public function encryptPhrase($passphrase, $public=true)
{
  if (strlen($passphrase)>1023) return false;
  if ($public)
  { // public key encrypt
  	if (!$this->public) return false;
    openssl_public_encrypt($passphrase, $passphraseEc, $this->public);
  }
  else
  { // private key encrypt
  	  if (!$this->private) return false;
      openssl_private_encrypt($passphrase, $passphraseEc, $this->private);
  }
  $passphraseEc=base64_encode($passphraseEc);
  return $passphraseEc;
} // encryptPhrase

/**
 * decrypts $passphrase using JCryptKey
 * uses asymmetric crypting
 * only for phrases lesser then 1023 bytes
 * useful for passphrase crypting, signing hashes, etc.
 * @param $passphrase phrase string to decrypt
 * @param $private if false decrypts using public key (signed data check) - default true (private key usage)
 * @return String encrypted phrase, false if error
 */
public function decryptPhrase($passphraseEc, $private=true)
{
  $passphraseEc=base64_decode($passphraseEc);
  if ($private)
  { // private key decrypt
  	  if (!$this->private) return false;
      var_dump(openssl_private_decrypt($passphraseEc, $passphraseDe, $this->private));
  }
  else
  { // public key decrypt
    if (!$this->public) return false;
    openssl_public_decrypt($passphraseEc, $passphraseDe, $this->public);
  }
  return $passphraseDe;
} // decryptPhrase

/**
 * gets a public key
 * @return String public key
 */
public function getPublicKey()
{
  return (string)$this->public;
} // getPublicKey

/**
 * gets a private key
 * @return String private key
 */
public function getPrivateKey()
{
  return (string)$this->private;
} // getPrivateKey

/**
 * gets a hash
 * @return String
 */
public function getHash()
{
  return (string)$this->tcrypt_hash;
} // getHash

/**
 * sets a hash
 * @param $hash
 * @return Boolean true if ok, false if not
 */
public function setHash($hash)
{
  if (strlen($hash)!=32)
  {
    //$this->errors->addError('COM_ICAF_TCRYPT_HASH_NOT_CORRECT_SIZE');
    return false;
  }
  $this->tcrypt_hash=(string)$hash;
  return true;
} // setHash

/**
 * stores tcrypt info in db
 * @return Boolean true if ok, false if not
 */
public function store()
{
	// upravime podle nasich pozadavku
	
	/*
  $tableRow=JTable::getInstance('tcrypt', 'Table');
  if (!isset($this->public))
  {
    //$this->errors->addError('COM_ICAF_TCRYPT_NO_PUBLIC_KEY');
    return false;
  }
  if (!$this->tcrypt_hash) $this->hashPKey();
  if (!$tableRow->bind(get_object_vars($this)))
  {
    //$this->errors->addError('COM_ICAF_TCRYPT_BIND_ERROR');
    return false;
  }  
  $datetime=date("Y-m-d H:i:s");
  $tableRow->modified=$datetime;
  $tableRow->deleted=0;
  if (!$tableRow->created) { $tableRow->created=$datetime; }
  if (!$tableRow->check())
  {
    //$this->errors->addError('COM_ICAF_TCRYPT_CHECK_ERROR');
    return false;
  }
  if (!$tableRow->store())
  {
    //$this->errors->addError('COM_ICAF_TCRYPT_STORE_ERROR');
    return false;
  }
  $this->tcrypt_id=$tableRow->tcrypt_id;
  return true;
*/
} // store

/**
 * loads tcrypt info from db and binds them to this
 */
public function load()
{
	// upravime podle nasich pozadavku

/*
  $db=JFactory::getDBO();
  $query=$db->getQuery(TRUE);
  $query->select("t.*");
  $query->from("#__icaf_tcrypts as t");
  if (isset($this->tcrypt_id)) $query->where("t.tcrypt_id='".$this->tcrypt_id."'");
  else $query->where("t.tcrypt_hash='".$this->tcrypt_hash."'");
  $query->where("t.deleted=0");
  $db->setQuery($query);
  $src=$db->loadObject();
  if (!$src)
  {
    //$this->errors->addError('COM_ICAF_TCRYPT_NOTHING_TO_LOAD_OR_DELETED');
    return false;
  }
  $src=get_object_vars($src);
  foreach (get_object_vars($this) as $k => $v)
  {
    if (isset($src[$k]))
    {
      $this->$k = $src[$k];
    }
  }  
  return true;
*/ 
} // load

/**
 * makes a hash of a public key as an id and binds it
 * @return Boolean true if ok
 */
protected function hashPKey()
{
  // start custom hash mechanism
  $hash=sha1($this->public);
  for ($i=0; $i < $this->tRev; $i++) $hash=sha1(md5($hash.$this->tSalt).$this->public.$this->tSalt);
  $hash=md5($hash);
  // end custom hash mechanism
  $this->tcrypt_hash=$hash;
  return true;
} // hashPKey

/**
 * deletes row from db, sets deleted 1
 * @return true if ok, false if not
 */
public function delete($hash=null)
{
	// upravime podle nasich pozadavku
		
/*	
  $tableRow=JTable::getInstance('tcrypt', 'Table');
  if (!isset($hash) || strlen($hash)!=32) $hash=$this->tcrypt_hash;
  if (!isset($hash))
  {
    //$this->errors->addError('COM_ICAF_TCRYPT_HASH_NOT_SET');
    return false;
  }
  $keys=array('tcrypt_hash'=>$hash);
  if (!$tableRow->load($keys))
  {
    //$this->errors->addError('COM_ICAF_TCRYPT_DELETE_ERROR');
    return false;
  }
  if ($tableRow->deleted==1)
  {
    //$this->errors->addError('COM_ICAF_TCRYPT_ALREADY_DELETED');
    return false;
  }
  else $tableRow->deleted=1;
  $datetime=date("Y-m-d H:i:s");
  $tableRow->modified=$datetime;
  if (!$tableRow->check())
  {
    //$this->errors->addError('COM_ICAF_TCRYPT_CHECK_ERROR');
    return false;
  }
  if (!$tableRow->store())
  {
    //$this->errors->addError('COM_ICAF_TCRYPT_STORE_ERROR');
    return false;
  }
  return true;
 */ 
} // deleteKeyPair

/**
 * sets passphrase
 * @return Boolean true if ok
 */
public function setPassphrase($passphrase)
{
  if (!$this->checkPassComplexity($passphrase))
  {
    //$this->errors->addError('COM_ICAF_TCRYPT_PASSPHRASE_COMPLEXITY_ERROR');
    return false;
  }
  $this->passphrase=$passphrase;
  return true;
} // setPassphrase

/**
 * checks passphrase (password) complexity
 * @param $passphrase
 * @return Boolean true if complexity ok, false if not
 */
protected function checkPassComplexity($passphrase)
{
  if (strlen($passphrase)<$this->minPassLen) return false;
  // TODO possibly some checks on number, upper, lower case character
	return true;
} // checkPassComplexity

/**
 * sets private key
 */
public function setPrivateKey($private)
{
	$this->private=$private;
}

/**
 * sets public key
 */
public function setPublicKey($public)
{
	$this->public=$public;
}

} // class
?>