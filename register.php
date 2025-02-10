<?php
$page_title = "สมัครสมาชิก";
require_once 'config/database.php';
require_once 'components/functions.php';
require_once 'components/header.php';
?>

<div class="bg-white p-8 rounded-lg shadow-md w-96">
    <h2 class="text-2xl font-bold mb-6">สมัครสมาชิก</h2>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = sanitize_input($_POST['name']);
        $email = sanitize_input($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $errors = [];

        // Validation
        if (empty($name)) {
            $errors[] = "กรุณากรอกชื่อ-นามสกุล";
        }
        
        if (empty($email)) {
            $errors[] = "กรุณากรอกอีเมล";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "รูปแบบอีเมลไม่ถูกต้อง";
        }

        if (empty($password)) {
            $errors[] = "กรุณากรอกรหัสผ่าน";
        } elseif (!validate_password($password)) {
            $errors[] = "รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัว และประกอบด้วยตัวอักษรพิมพ์ใหญ่และตัวเลข";
        }

        if ($password !== $confirm_password) {
            $errors[] = "รหัสผ่านไม่ตรงกัน";
        }

        // Check if email already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "อีเมลนี้ถูกใช้งานแล้ว";
        }

        if (empty($errors)) {
            try {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $hashed_password]);
                $_SESSION['success'] = "สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ";
                header("Location: login.php");
                exit();
            } catch(PDOException $e) {
                $errors[] = "เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง";
            }
        }

        display_errors($errors);
    }
    ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                ชื่อ-นามสกุล
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   id="name" type="text" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                อีเมล
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   id="email" type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                รหัสผ่าน
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                   id="password" type="password" name="password">
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">
                ยืนยันรหัสผ่าน
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                   id="confirm_password" type="password" name="confirm_password">
        </div>
        
        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
                    type="submit">
                สมัครสมาชิก
            </button>
        </div>
        
        <div class="text-center mt-4">
            <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800"
               href="login.php">
                มีบัญชีอยู่แล้ว? เข้าสู่ระบบ
            </a>
        </div>
    </form>
</div>

