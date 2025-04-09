<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $names = $_POST['names'] ?? [];
    $urls = $_POST['urls'] ?? [];

    if (count($names) !== count($urls) || count($names) === 0) {
        echo "<p style='color: red;'>Input tidak valid. Pastikan jumlah server dan URL sesuai.</p>";
        exit;
    }

    // Generating the iframe code
    $firstUrl = htmlspecialchars($urls[0]);
    $iframeCode = '<div class="div-frame">
                    <div class="frame">
                        <iframe width="600" height="480" src="' . $firstUrl . '" allow="autoplay; encrypted-media" scrolling="no" frameborder="0" allowfullscreen id="iframeResult"></iframe>
                    </div>
                    <ul class="tab-server" id="serverid">
                        <li class="server">Server +
                            <ul class="sub-server">';

    foreach ($names as $i => $name) {
        $url = htmlspecialchars($urls[$i]);
        $iframeCode .= '<li class="btn ' . ($i === 0 ? 'active' : '') . '">
                            <a href="javascript:void(0)" onclick="go(\'' . $url . '\')">' . htmlspecialchars($name) . '</a>
                        </li>';
    }

    $iframeCode .= '   </ul>
                    </li>
                </ul>
            </div>
            <script>
                function go(loc) {
                    document.getElementById("iframeResult").src = loc;
                }
                var btnContainer = document.getElementById("serverid");
                var btns = btnContainer.getElementsByClassName("btn");
                for (var i = 0; i < btns.length; i++) {
                    btns[i].addEventListener("click", function() {
                        var current = document.getElementsByClassName("active");
                        if (current.length > 0) {
                            current[0].classList.remove("active");
                        }
                        this.classList.add("active");
                    });
                }
            </script>';

    echo "$iframeCode";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Generator Iframe Multi-Server</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-8 px-4">

    <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Generator Iframe Multi-Server</h2>
    
    <form id="iframeForm" method="POST" class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-lg space-y-4">
        <div>
            <label class="block text-lg font-semibold text-gray-700">Nama Server</label>
            <input type="text" name="names[]" placeholder="Contoh: Blogger 720p" required class="w-full p-3 border border-gray-300 rounded-lg mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-lg font-semibold text-gray-700">URL Iframe</label>
            <input type="text" name="urls[]" placeholder="Contoh: https://contoh.com/player1" required class="w-full p-3 border border-gray-300 rounded-lg mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div id="moreServers"></div>

        <button type="button" onclick="addMore()" class="w-full py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300 mt-4">+ Tambah Server</button>

        <button type="submit" class="w-full py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-300 mt-4">Generate</button>
    </form>

    <div id="result" class="mt-8">
        <!-- Result will be displayed here in a card -->
    </div>

    <script>
        function addMore() {
            const html = `
                <div class="server-container bg-gray-50 p-4 rounded-lg mb-4">
                    <div>
                        <label class="block text-lg font-semibold text-gray-700">Nama Server</label>
                        <input type="text" name="names[]" required class="w-full p-3 border border-gray-300 rounded-lg mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-lg font-semibold text-gray-700">URL Iframe</label>
                        <input type="text" name="urls[]" required class="w-full p-3 border border-gray-300 rounded-lg mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="button" class="mt-2 py-2 px-4 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-300" onclick="removeServer(this)">Hapus Server</button>
                </div>
            `;
            document.getElementById('moreServers').insertAdjacentHTML('beforeend', html);
        }

        function removeServer(button) {
            button.closest('.server-container').remove();
        }

        document.getElementById('iframeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('', {
                method: 'POST',
                body: formData
            }).then(res => res.text()).then(html => {
                document.getElementById('result').innerHTML = `
                    <div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl mx-auto">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Hasil Iframe</h3>
                        <textarea rows="10" class="w-full p-3 border border-gray-300 rounded-lg" readonly>${html}</textarea>
                    </div>
                `;
            });
        });
    </script>
</body>
</html>
