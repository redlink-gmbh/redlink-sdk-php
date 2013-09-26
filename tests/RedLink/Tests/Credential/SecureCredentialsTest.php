<?php

namespace RedLink\Tests\Credentials;

/**
 * <p>Secure Credentials Tests</p>
 *
 * @author Antonio David Pérez Morales <adperezmorales@gmail.com>
 * @covers RedLink\Credential\SecureCredentials
 */
class SecureCredentialsTest extends \PHPUnit_Framework_TestCase
{
    private $credentials;
    private static $API_KEY_VALUE = "APIKEYVALUE";
    
    private static $REDLINK_CA_FILE;
    
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$REDLINK_CA_FILE = REDLINK_ROOT_PATH . DIRECTORY_SEPARATOR . 'redlink-CA.pem';
    }
    
    protected function setUp()
    {
        parent::setUp();
        $this->credentials = new \RedLink\Credential\SecureCredentials(self::$API_KEY_VALUE);
    }
    
    protected function tearDown()
    {
        parent::tearDown();
    }
    
    /**
     * @covers RedLink\Credentials\DefaultCredentials::getEndpoint
     */
    public function testEndpoint() {
        $this->assertNotEmpty($this->credentials->getEndpoint());
        $this->assertEquals('https://beta.redlink.io/1.0-ALPHA/', $this->credentials->getEndpoint());
    }

    /**
     * @covers RedLink\Credentials\SecureCredentials::setSSLCertificates
     * @covers RedLink\Credentials\SecureCredentials::getSSLCertificates
     */
    public function testSSLCertificates() {
        $this->credentials->setSSLCertificates(self::$REDLINK_CA_FILE);
        $this->assertEquals(self::$REDLINK_CA_FILE, $this->credentials->getSSLCertificates());
    }
    
    /**
     * @covers RedLink\Credentials\SecureCredentials::setSSLCertificates
     * @expectedException \RuntimeException
     */
    public function testSSLWrongCertificates() {
        $this->credentials->setSSLCertificates('./unexistent_file');
    }
    
    /**
     * @covers RedLink\Credentials\DefaultCredentials::buildUrl
     */
    public function testBuildUrl() {
        $this->assertNotEmpty($this->credentials->buildUrl($this->credentials->getEndpoint()));
        $client = $this->credentials->buildUrl($this->credentials->getEndpoint());
        $this->assertEquals('https://beta.redlink.io/1.0-ALPHA/?key='.self::$API_KEY_VALUE, $client->getBaseUrl(false));
    }
}

?>
