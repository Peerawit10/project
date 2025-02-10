<?php
$page_title = "เข้าสู่ระบบ";
require_once 'config/database.php';
require_once 'components/functions.php';
require_once 'components/header.php';
?>

<div class="bg-white p-8 rounded-lg shadow-md w-96">
    <h2 class="text-2xl font-bold mb-6">เข้าสู่ระบบ</h2>
    
    <?php
    if (isset($_SESSION['success'])) {
        display_success($_SESSION['success']);
        unset($_SESSION['success']);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = sanitize_input($_POST['email']);
        $password = $_POST['password'];
        $errors = [];

        if (empty($email) || empty($password)) {
            $errors[] = "กรุณากรอกอีเมลและรหัสผ่าน";
        } else {
            try {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    header("Location: index.php");
                    exit();
                } else {
                    $errors[] = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
                }
            } catch(PDOException $e) {
                $errors[] = "เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง";
            }
        }

        display_errors($errors);
    }
    ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                อีเมล
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   id="email" type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                รหัสผ่าน
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                   id="password" type="password" name="password">
        </div>
        
        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
                    type="submit">
                เข้าสู่ระบบ
            </button>
        </div>
        
        <div class="text-center mt-4">
            <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800"
               href="register.php">
                ยังไม่มีบัญชี? สมัครสมาชิก
            </a>
        </div>
    </form>
</div>

