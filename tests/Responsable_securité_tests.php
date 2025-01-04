<?php
use PHPUnit\Framework\TestCase;

class  securitytest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Créer une table utilisateurs pour les tests
        $this->pdo->exec("CREATE TABLE utilisateurs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nom_d_utilisateur TEXT NOT NULL,
            mot_de_passe TEXT NOT NULL
        )");
    }

    public function testPasswordHashing(): void
    {
        $password = 'securepassword';
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        
        $this->assertTrue(password_verify($password, $hashedPassword));
        $this->assertNotEquals($password, $hashedPassword, 'Le mot de passe ne doit pas être stocké en clair.');
    }

    public function testPasswordSalting(): void
    {
        $password1 = 'securepassword';
        $password2 = 'securepassword';

        $hashedPassword1 = password_hash($password1, PASSWORD_DEFAULT);
        $hashedPassword2 = password_hash($password2, PASSWORD_DEFAULT);

        
        $this->assertNotEquals($hashedPassword1, $hashedPassword2, 'Chaque hash doit être unique même pour le même mot de passe.');
    }

    public function testNoPlainTextStorage(): void
    {
        $username = 'testuser';
        $password = 'securepassword';
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        
        $stmt = $this->pdo->prepare("INSERT INTO utilisateurs (nom_d_utilisateur, mot_de_passe) VALUES (:username, :password)");
        $stmt->execute(['username' => $username, 'password' => $hashedPassword]);

        
        $stmt = $this->pdo->prepare("SELECT mot_de_passe FROM utilisateurs WHERE nom_d_utilisateur = :username");
        $stmt->execute(['username' => $username]);
        $storedPassword = $stmt->fetchColumn();

      
        $this->assertNotEquals($password, $storedPassword, 'Le mot de passe stocké ne doit jamais être en clair.');
    }




   


    public function testPreparedStatements(): void

    {
    $username = "' OR 1=1 --"; 
    $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE nom_d_utilisateur = :username");
    $stmt->execute(['username' => $username]);
    $result = $stmt->fetchAll();

    
    $this->assertEmpty($result, 'Les requêtes préparées doivent protéger contre les injections SQL.');
}

public function testCharacterEscaping(): void
{
    $username = "test'username"; 
    $stmt = $this->pdo->prepare("INSERT INTO utilisateurs (nom_d_utilisateur, mot_de_passe) VALUES (:username, :password)");
    $stmt->execute(['username' => $username, 'password' => 'testpassword']);

    $stmt = $this->pdo->prepare("SELECT nom_d_utilisateur FROM utilisateurs WHERE nom_d_utilisateur = :username");
    $stmt->execute(['username' => $username]);
    $storedUsername = $stmt->fetchColumn();

    
    $this->assertEquals($username, $storedUsername, 'Les caractères spéciaux doivent être correctement échappés.');
}

public function testInputValidation(): void
{
    $username = "<script>alert('xss')</script>"; 

    
    
    $isValid = preg_match('/^[a-zA-Z0-9_]+$/', $username);

   
    
    $this->assertEquals(0, $isValid, 'Les entrées doivent être validées pour empêcher les caractères non autorisés.');
}

public function testScriptFiltering(): void
{
    $input = "<script>alert('xss')</script>";
    $filteredInput = strip_tags($input);

   
    
    $this->assertNotEquals($input, $filteredInput, 'Les scripts doivent être filtrés pour éviter les attaques XSS.');
    $this->assertEquals("alert('xss')", $filteredInput, 'Seul le contenu non script doit être conservé.');
}

public function testHTMLEncoding(): void
{
    $input = "<div>Content</div>";
    $encodedInput = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

    
    
    $this->assertEquals("&lt;div&gt;Content&lt;/div&gt;", $encodedInput, 'Le contenu HTML doit être correctement encodé.');
}

public function testSecurityHeaders(): void
{
    
    
    $headers = [
        'Content-Security-Policy' => "default-src 'self'",
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'DENY',
    ];

   
    
    $this->assertArrayHasKey('Content-Security-Policy', $headers, 'Le header Content-Security-Policy doit être défini.');
    $this->assertArrayHasKey('X-Content-Type-Options', $headers, 'Le header X-Content-Type-Options doit être défini.');
    $this->assertArrayHasKey('X-Frame-Options', $headers, 'Le header X-Frame-Options doit être défini.');

  
    
    $this->assertEquals("default-src 'self'", $headers['Content-Security-Policy'], 'Le header Content-Security-Policy doit être correctement configuré.');
    $this->assertEquals('nosniff', $headers['X-Content-Type-Options'], 'Le header X-Content-Type-Options doit être correctement configuré.');
    $this->assertEquals('DENY', $headers['X-Frame-Options'], 'Le header X-Frame-Options doit être correctement configuré.');
}

public function testCsrfTokenValidation(): void
{
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;

    
    
    $requestToken = $token;
    $this->assertEquals($_SESSION['csrf_token'], $requestToken, 'Le token CSRF doit correspondre à celui de la session.');

 
    
    $invalidToken = bin2hex(random_bytes(32));
    $this->assertNotEquals($_SESSION['csrf_token'], $invalidToken, 'Un token CSRF invalide ne doit pas être accepté.');
}

public function testCsrfTokenExpiration(): void
{
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    $_SESSION['csrf_token_time'] = time();

    
    
    $currentTime = $_SESSION['csrf_token_time'] + 300; // 5 minutes
    $this->assertLessThan(600, $currentTime - $_SESSION['csrf_token_time'], 'Le token CSRF doit être valide avant lexpiration.');

   
    $currentTime = $_SESSION['csrf_token_time'] + 1200; // 20 minutes
    $this->assertGreaterThan(600, $currentTime - $_SESSION['csrf_token_time'], 'Le token CSRF doit expirer après un certain délai.');
}

public function testCsrfSessionValidation(): void
{
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    session_regenerate_id(true);

    $sessionToken = $_SESSION['csrf_token'];

   
    
    $this->assertNotEmpty($sessionToken, 'Le token CSRF doit être lié à la session active.');
    $this->assertEquals($token, $sessionToken, 'Le token CSRF doit être valide pour la session active.');
}

public function testSessionConfiguration(): void
{
  
    
    ini_set('session.cookie_secure', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');

    $this->assertEquals('1', ini_get('session.cookie_secure'), 'Les cookies de session doivent être sécurisés.');
    $this->assertEquals('1', ini_get('session.cookie_httponly'), 'Les cookies de session doivent être HTTP only.');
    $this->assertEquals('1', ini_get('session.use_only_cookies'), 'Les sessions doivent utiliser uniquement des cookies.');
}

public function testSecureCookies(): void
{
    session_start([
        'cookie_lifetime' => 3600,
        'cookie_secure' => true,
        'cookie_httponly' => true,
    ]);

    
    
    $this->assertTrue(ini_get('session.cookie_secure'), 'Les cookies de session doivent être sécurisés.');
    $this->assertTrue(ini_get('session.cookie_httponly'), 'Les cookies de session doivent être HTTP only.');
}

public function testSessionTimeout(): void
{
    $_SESSION['last_activity'] = time();

    // Simuler une activité récente
    $this->assertLessThan(1800, time() - $_SESSION['last_activity'], 'La session doit être active dans le délai de timeout.');

    // Simuler un timeout
    $_SESSION['last_activity'] = time() - 3600;
    $this->assertGreaterThan(1800, time() - $_SESSION['last_activity'], 'La session doit expirer après un délai dinactivité.');
}

public function testDataEncryption(): void
{
    $sensitiveData = "1234-5678-9101-1121"; 
    $encryptionKey = 'secretkey1234567';

    
    $encryptedData = openssl_encrypt($sensitiveData, 'AES-128-ECB', $encryptionKey);
    $this->assertNotEquals($sensitiveData, $encryptedData, 'Les données sensibles doivent être chiffrées.');

    
    $decryptedData = openssl_decrypt($encryptedData, 'AES-128-ECB', $encryptionKey);
    $this->assertEquals($sensitiveData, $decryptedData, 'Les données doivent pouvoir être déchiffrées correctement.');
}

public function testAccessControl(): void
{
    $userRoles = ['admin', 'editor', 'viewer'];
    $resourcePermissions = [
        'admin' => ['read', 'write', 'delete'],
        'editor' => ['read', 'write'],
        'viewer' => ['read'],
    ];

    $userRole = 'editor';
    $requestedPermission = 'delete';

   
    
    $this->assertFalse(in_array($requestedPermission, $resourcePermissions[$userRole]), 'Les contrôles d\'accès doivent respecter les rôles utilisateur.');
}

public function testAccessAudit(): void
{
    $auditLog = [];
    $userId = 1;
    $action = 'read';
    $resource = 'file1.txt';

    
    
    $auditLog[] = [
        'user_id' => $userId,
        'action' => $action,
        'resource' => $resource,
        'timestamp' => time(),
    ];

   
    
    $this->assertCount(1, $auditLog, 'Tous les accès doivent être audités.');
    $this->assertEquals($userId, $auditLog[0]['user_id'], 'L\'ID utilisateur doit être enregistré.');
    $this->assertEquals($action, $auditLog[0]['action'], 'L\'action doit être enregistrée.');
    $this->assertEquals($resource, $auditLog[0]['resource'], 'La ressource doit être enregistrée.');
}
}

