<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NHỊP SỐNG MỚI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header class="top-header">
        <div class="container header-flex">
            <div class="header-left">
                <a href="index.php" class="logo">
                    <h2>NHỊP SỐNG<span> MỚI</span></h2>
                </a>
                <div class="meta-info">
                    <span id="current-date"></span> |
                    <span id="user-location"><i class="fas fa-map-marker-alt"></i> Hà Nội</span> |
                    <span id="weather-info"><i class="fas fa-cloud-sun"></i> 26°C</span>
                </div>
            </div>
            <div class="header-right" style="display:flex; align-items:center; gap: 15px;">
                <div class="search-box">
                    <form action="index.php" method="GET" style="display: flex; width: 100%;">
                        <input type="hidden" name="controller" value="search">
                        <input type="text" name="q" placeholder="Tìm kiếm tin tức..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>" required>
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                    <button id="darkModeBtn" style="background: none; border: none; font-size: 1.5rem; color: #555; cursor: pointer; transition: 0.3s;" title="Chế độ Tối/Sáng">
                      <i class="fas fa-moon"></i>
                    </button>
                </div>

                <button id="ai-toggle-btn" title="Trợ lý AI" style="background: none; border: none; color: inherit; font-size: 1.3rem; cursor: pointer; transition: 0.2s;">
                    <i class="fas fa-robot"></i>
                </button>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="user-area" onclick="toggleUserMenu()">
                        <img src="<?= !empty($_SESSION['avatar']) ? $_SESSION['avatar'] : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['username']) . '&background=random' ?>" alt="Avatar" class="avatar">
                        <div class="dropdown-menu" id="userDropdown">
                            <div class="user-info">Xin chào, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></div>
                            <hr>
                            <?php if(isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'bien_tap_vien')): ?>
                             <a href="index.php?controller=admin"><i class="fas fa-cog"></i> Trang quản trị</a>
                            <?php endif; ?>
                            <a href="index.php?controller=user&action=profile"><i class="fas fa-user-circle"></i> Thông tin cá nhân</a>
                            <a href="index.php?controller=user&action=saved"><i class="fas fa-bookmark"></i> Tin đã lưu</a>
                            <a href="index.php?controller=user&action=history"><i class="fas fa-history"></i> Lịch sử đọc báo</a>
                            <hr>
                            <a href="index.php?controller=logout" class="logout" style="color: #d32f2f;"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div style="display: flex; gap: 15px; align-items: center; margin-left: 10px;">
                        <a href="index.php?controller=login" style="font-weight: 600; color: #555;">Đăng nhập</a>
                        <a href="index.php?controller=register" style="background: #b71c1c; color: #fff; padding: 6px 15px; border-radius: 20px; font-weight: bold; font-size: 0.9rem;">Đăng ký</a>
                    </div>
                <?php endif; ?>
                </div>
        </div>
    </header>

    <nav class="main-nav">
        <div class="container">
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i></a></li>
                <?php if(isset($categories)): foreach($categories as $cat): ?>
                    <li><a href="index.php?controller=category&slug=<?= $cat['slug'] ?>"><?= htmlspecialchars($cat['name']) ?></a></li>
                <?php endforeach; endif; ?>
            </ul>
        </div>
    </nav>