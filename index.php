<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $names = $_POST['names'] ?? [];
    $urls = $_POST['urls'] ?? [];
    if (count($names) !== count($urls) || count($names) === 0) {
        echo "<p style='color: red;'>Input tidak valid. Pastikan jumlah server dan URL sesuai.</p>";
        exit;
    }
    $firstUrl = htmlspecialchars($urls[0]);
    $iframeCode = '<div class="div-frame"><div class="frame"><iframe src="' . $firstUrl . '" allow="autoplay; encrypted-media" scrolling="no" frameborder="0" allowfullscreen id="iframeResult"></iframe></div><ul class="tab-server" id="serverid"><li class="server">Server +<ul class="sub-server">';
    foreach ($names as $i => $name) {
        $url = htmlspecialchars($urls[$i]);
        $iframeCode .= '<li class="btn ' . ($i === 0 ? 'active' : '') . '"><a href="javascript:void(0)" onclick="go(\'' . $url . '\')">' . htmlspecialchars($name) . '</a></li>';
    }
    $iframeCode .= '</ul></li></ul></div><script>function go(loc) { document.getElementById("iframeResult").src = loc; } var btnContainer = document.getElementById("serverid"); var btns = btnContainer.getElementsByClassName("btn"); for (var i = 0; i < btns.length; i++) { btns[i].addEventListener("click", function() { var current = document.getElementsByClassName("active"); if (current.length > 0) { current[0].classList.remove("active"); } this.classList.add("active"); }); }</script>';
    echo "$iframeCode";
    exit;
} ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Multi Iframe</title>
    <link rel="icon" href="https://s.w.org/favicon.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-200 to-indigo-300 min-h-screen flex items-center justify-center">
    <div class="container mx-auto p-5 space-y-10">
        <div class="bg-white p-8 rounded-2xl shadow-xl max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-center text-gray-900 mb-6">Generate Multi Iframe Code</h1>
            <form id="iframeForm" method="POST" class="space-y-6">
                <div class="space-y-4">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="flex-1">
                            <label class="block text-lg font-medium text-gray-800">Nama Server</label>
                            <input type="text" name="names[]" placeholder="Contoh: Blogger 720p" required class="w-full p-4 border border-gray-300 rounded-xl mt-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all ease-in-out">
                        </div>
                        <div class="flex-1">
                            <label class="block text-lg font-medium text-gray-800">Link Embed</label>
                            <input type="text" name="urls[]" placeholder="Contoh: https://contoh.com/player1" required class="w-full p-4 border border-gray-300 rounded-xl mt-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all ease-in-out">
                        </div>
                    </div>
                    <div id="moreServers"></div>
                    <div class="flex justify-between items-center">
                        <button type="button" onclick="addMore()" class="py-2 px-6 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">+ Tambah Server</button>
                        <button type="submit" class="py-2 px-6 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-300">Generate</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-xl max-w-4xl mx-auto">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Hasil Iframe</h3>
            <textarea id="result" rows="10" class="w-full p-4 border border-gray-300 rounded-xl" readonly onclick="copyToClipboard()"></textarea>
        </div>
    </div>

    <script>
        function copyToClipboard() {
            const resultText = document.getElementById('result');
            resultText.select();
            resultText.setSelectionRange(0, 99999);
            document.execCommand('copy');
        }

        function addMore() {
            const html = `<div class="server-container mb-4">
                            <div class="flex flex-col md:flex-row gap-6">
                                <div class="flex-1">
                                    <label class="block text-lg font-medium text-gray-800">Nama Server</label>
                                    <input type="text" name="names[]" placeholder="Contoh: Blogger 720p" required class="w-full p-4 border border-gray-300 rounded-xl mt-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-lg font-medium text-gray-800">Link Embed</label>
                                    <input type="text" name="urls[]" placeholder="Contoh: https://contoh.com/player1" required class="w-full p-4 border border-gray-300 rounded-xl mt-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                            <button type="button" class="mt-2 py-2 px-6 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-300" onclick="removeServer(this)">Hapus Server</button>
                        </div>`;
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
                document.getElementById('result').innerHTML = `${html}`;
            });
        });
    </script>
</body>

</html>