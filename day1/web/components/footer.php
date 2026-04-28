<footer style="margin-top: auto; text-align: center; padding: 24px; border-top: 1px solid #ddd; background: #fff; border-radius: 8px;">
    <h4 style="margin-bottom: 12px; color: var(--primary-text-color);">quick nav</h4>
    <div id="footer-links" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 8px;">
        <!-- Links dynamically inserted by JS -->
    </div>
</footer>

<script>
    (function () {
        var links = [
            { name: "Beranda (Home)", path: "/index.php" },
            { name: "Login", path: "/auth/login.php" },
            { name: "Dashboard user", path: "/user/dashboard.php" },
            { name: "Kelola Post", path: "/user/upload.php" },
            { name: "Posting", path: "/user/posting.php" },
            { name: "Kelola Kategori", path: "/user/kategori.php" },
            { name: "Tambah Kategori", path: "/user/tambah_kategori.php" },
            { name: "Register", path: "/auth/register.php" },
            { name: "Logout", path: "/auth/logout.php" }
        ];

        var pathParts = window.location.pathname.split("/");
        var rootIndex = pathParts.indexOf("web");
        var basePath = "";
        
        if (rootIndex !== -1) {
            basePath = pathParts.slice(0, rootIndex + 1).join("/");
        }

        var container = document.getElementById("footer-links");
        if (container) {
            links.forEach(function(item) {
                var a = document.createElement("a");
                a.href = basePath + item.path;
                a.textContent = item.name;
                a.style.cssText = "display: inline-block; padding: 6px 12px; background: #f3faff; border: 1px solid #2294ed; border-radius: 4px; color: #2294ed; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: background 0.2s;";
                a.onmouseover = function() { this.style.background = '#2294ed'; this.style.color = '#fff'; };
                a.onmouseout = function() { this.style.background = '#f3faff'; this.style.color = '#2294ed'; };
                container.appendChild(a);
            });
        }
    })();
</script>
