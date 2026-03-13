<?php include __DIR__ . '/partials/header.php'; ?>

<main class="container main-body" style="justify-content: center; margin-top: 50px; margin-bottom: 100px;">
    <div
        style="background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 450px;">
        <h2 style="text-align: center; color: #b71c1c; margin-bottom: 25px; font-weight: 800;">ĐĂNG KÝ TÀI KHOẢN</h2>

        <?php if (!empty($error)): ?>
            <div
                style="background: #ffebee; color: #c62828; padding: 10px; border-radius: 4px; margin-bottom: 20px; text-align: center;">
                <?= $error ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div
                style="background: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 4px; margin-bottom: 20px; text-align: center;">
                <?= $success ?></div>
        <?php endif; ?>

        <form action="index.php?controller=register" method="POST">
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Họ và tên hiển thị</label>
                <input type="text" name="username" required
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; outline: none;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Email</label>
                <input type="email" name="email" required
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; outline: none;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Mật khẩu</label>
                <input type="password" name="password" required
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; outline: none;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Nhập lại mật khẩu</label>
                <input type="password" name="re_password" required
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; outline: none;">
            </div>
            <button type="submit"
                style="width: 100%; padding: 12px; background: #b71c1c; color: #fff; border: none; border-radius: 4px; font-size: 1.1rem; font-weight: bold; cursor: pointer;">Đăng
                Ký</button>
        </form>

        <p style="text-align: center; margin-top: 20px; color: #666;">
            Đã có tài khoản? <a href="index.php?controller=login" style="color: #b71c1c; font-weight: bold;">Đăng
                nhập</a>
        </p>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>