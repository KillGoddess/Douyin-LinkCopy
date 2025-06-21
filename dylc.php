<?php
// 处理用户输入的抖音分享链接
function processDouyinLinks($input) {
    $lines = explode("\n", $input);
    $results = [];
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        // 匹配开头的4个部分（顺序可能不同）
        $pattern = '/^(?:\d{1,2}\.\d{1,2}\s+|\w{3}:\/\s+|\d{1,2}\/\d{1,2}\s+|\S+?@\S+?\.\w{2}\s+){4}/i';
        
        // 去除开头的4个部分
        $cleanText = preg_replace($pattern, '', $line);
        
        // 去除从https://开始到结尾的所有内容
        $httpsPos = stripos($cleanText, 'https://');
        if ($httpsPos !== false) {
            $cleanText = substr($cleanText, 0, $httpsPos);
        }
        
        // 将"# "替换为"#"
        $cleanText = str_replace('# ', '#', $cleanText);
        
        // 去除多余空格并添加到结果
        $results[] = trim($cleanText);
    }
    
    return implode("\n", $results);
}

// 处理表单提交
$result = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputText = $_POST['input_text'] ?? '';
    $result = processDouyinLinks($inputText);
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>抖音分享链接文案提取工具</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'PingFang SC', 'Microsoft YaHei', sans-serif;
        }
        
        :root {
            --primary: #8e2de2;
            --secondary: #4a00e0;
            --accent: #ff416c;
            --light: #f8f9fa;
            --dark: #2c3e50;
            --gray: #7f8c8d;
            --success: #00c853;
            --border: rgba(0, 0, 0, 0.1);
            --card-bg: rgba(255, 255, 255, 0.93);
        }
        
        body {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .container {
            width: 100%;
            max-width: 1000px;
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            position: relative;
        }
        
        .home-button {
            position: absolute;
            top: 25px;
            left: 25px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 30px;
            padding: 12px 25px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.4s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 20;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            text-decoration: none;
            font-weight: 500;
        }
        
        .home-button:hover {
            transform: translateX(10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        
        .home-button:hover i {
            transform: translateX(-5px);
        }
        
        .home-button i {
            transition: transform 0.3s ease;
        }
        
        header {
            padding: 80px 40px 30px;
            text-align: center;
            position: relative;
            background: linear-gradient(135deg, rgba(142, 45, 226, 0.1), rgba(74, 0, 224, 0.1));
            border-bottom: 1px solid var(--border);
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        
        .subtitle {
            color: var(--dark);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.7);
            padding: 10px 20px;
            border-radius: 30px;
            display: inline-block;
        }
        
        .content {
            padding: 30px 40px;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        
        .io-section {
            display: flex;
            gap: 30px;
            margin-bottom: 20px;
        }
        
        .input-section, .output-section {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(142, 45, 226, 0.2);
        }
        
        .section-header i {
            font-size: 1.6rem;
            margin-right: 15px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        h2 {
            font-size: 1.5rem;
            color: var(--dark);
            font-weight: 700;
        }
        
        .textarea-container {
            position: relative;
            height: 250px; /* 固定高度 */
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        textarea {
            width: 100%;
            height: 100%;
            padding: 20px;
            border: none;
            border-radius: 15px;
            font-size: 1rem;
            resize: none;
            background: rgba(248, 249, 250, 0.9);
            line-height: 1.6;
            overflow-y: auto; /* 启用垂直滚动条 */
        }
        
        /* 美化滚动条 */
        textarea::-webkit-scrollbar {
            width: 10px;
        }
        
        textarea::-webkit-scrollbar-track {
            background: rgba(142, 45, 226, 0.1);
            border-radius: 5px;
        }
        
        textarea::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, var(--primary), var(--secondary));
            border-radius: 5px;
        }
        
        textarea::-webkit-scrollbar-thumb:hover {
            background: var(--accent);
        }
        
        textarea:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(142, 45, 226, 0.3);
        }
        
        .output-section textarea {
            background: rgba(232, 244, 252, 0.9);
        }
        
        .button-container {
            margin-top: 20px;
            text-align: center;
        }
        
        .action-button {
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 16px 45px;
            font-size: 1.1rem;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.4s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            position: relative;
            overflow: hidden;
            z-index: 1;
            font-weight: 600;
            box-shadow: 0 8px 20px rgba(142, 45, 226, 0.4);
        }
        
        .action-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, var(--secondary), var(--primary));
            z-index: -1;
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        
        .action-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(142, 45, 226, 0.5);
        }
        
        .action-button:hover::before {
            opacity: 1;
        }
        
        .action-button:active {
            transform: translateY(2px);
        }
        
        .action-button i {
            transition: transform 0.3s ease;
        }
        
        .action-button:hover i {
            transform: scale(1.2);
        }
        
        .example-section {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(240, 240, 255, 0.9));
            border-radius: 15px;
            padding: 25px;
            border-left: 5px solid var(--primary);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .example-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .example-header i {
            color: var(--primary);
            margin-right: 15px;
            font-size: 1.8rem;
        }
        
        .example-content {
            font-size: 1rem;
            line-height: 1.7;
        }
        
        .example-row {
            margin-bottom: 25px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }
        
        .example-label {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.1rem;
        }
        
        .example-label i {
            color: var(--secondary);
            font-size: 1.4rem;
        }
        
        .example-text {
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
            background: rgba(0, 0, 0, 0.03);
            padding: 15px;
            border-radius: 10px;
            font-size: 0.95rem;
            line-height: 1.7;
            border-left: 3px solid var(--secondary);
        }
        
        .copy-notification {
            position: fixed;
            top: 30px;
            right: 30px;
            background: var(--success);
            color: white;
            padding: 18px 32px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transform: translateX(200%);
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 1.1rem;
            font-weight: 500;
        }
        
        .copy-notification.show {
            transform: translateX(0);
        }
        
        /* 装饰元素 */
        .decoration {
            position: absolute;
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, rgba(142, 45, 226, 0.15), rgba(74, 0, 224, 0.15));
            border-radius: 50%;
            filter: blur(60px);
            z-index: -1;
        }
        
        .dec-1 {
            top: 10%;
            left: 5%;
            animation: float 15s infinite ease-in-out;
        }
        
        .dec-2 {
            bottom: 15%;
            right: 5%;
            animation: float 18s infinite ease-in-out;
            animation-delay: 3s;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(-20px, 25px) rotate(-5deg); }
            50% { transform: translate(15px, -15px) rotate(5deg); }
            75% { transform: translate(20px, 15px) rotate(-3deg); }
        }
        
        @media (max-width: 900px) {
            .io-section {
                flex-direction: column;
            }
            
            .container {
                max-width: 95%;
            }
            
            header {
                padding: 70px 25px 25px;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .content {
                padding: 20px;
            }
            
            .home-button {
                top: 20px;
                left: 20px;
                padding: 10px 20px;
                font-size: 0.95rem;
            }
        }
        
        @media (max-width: 600px) {
            header {
                padding: 60px 20px 20px;
            }
            
            h1 {
                font-size: 1.7rem;
            }
            
            .subtitle {
                font-size: 0.95rem;
            }
            
            .section-header i {
                font-size: 1.4rem;
            }
            
            h2 {
                font-size: 1.3rem;
            }
            
            .action-button {
                padding: 14px 35px;
                font-size: 1rem;
            }
            
            .textarea-container {
                height: 220px;
            }
        }
    </style>
</head>
<body>
    <!-- 装饰元素 -->
    <div class="decoration dec-1"></div>
    <div class="decoration dec-2"></div>
    
    <div class="container">
        <button class="home-button">
            <i class="fas fa-arrow-left"></i> 返回主页
        </button>
        
        <header>
            <h1><i class="fab fa-tiktok"></i> 抖音文案提取工具</h1>
            <p class="subtitle">一键提取纯净文案内容，智能去除无关格式和链接</p>
        </header>
        
        <div class="content">
            <!-- 输入输出区域 -->
            <div class="io-section">
                <form method="post" class="input-section">
                    <div class="section-header">
                        <i class="fas fa-paste"></i>
                        <h2>输入抖音分享链接</h2>
                    </div>
                    <div class="textarea-container">
                        <textarea name="input_text" placeholder="请在此处粘贴抖音分享链接文本（可粘贴多条）..."><?php 
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['input_text'])) {
                                echo htmlspecialchars($_POST['input_text']);
                            } else {
                                echo "5.30 P@k.CU WMw:/ 04/07 # 出差旅行必备 这个真的住酒店一定要提前备一些，或者睡火车卧铺，那个枕头更要套上这种一次性枕套，便宜好用，小巧便携！# 一次性枕套 # 出差旅行必备 # 好物推荐🔥 https://v.douyin.com/4FJdG6AEPNo/ 复制此链接，打开Dou音搜索，直接观看视频！\n9.79 nQK:/ 01/31 u@s.eB 【六块九/两条】裤脚链条磁吸扣固定磁吸衬衫袖子变短收袖口免缝# 好物分享 # 夏日必备 # 仙女必备 # 每日分享 # 好物推荐分享  https://v.douyin.com/324rE8g31cg/ 复制此链接，打开Dou音搜索，直接观看视频!";
                            }
                        ?></textarea>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="action-button">
                            <i class="fas fa-wand-magic-sparkles"></i> 提取文案
                        </button>
                    </div>
                </form>
                
                <div class="output-section">
                    <div class="section-header">
                        <i class="fas fa-file-alt"></i>
                        <h2>提取结果</h2>
                    </div>
                    <div class="textarea-container">
                        <textarea id="result" readonly placeholder="提取的文案将显示在这里..."><?php echo htmlspecialchars($result); ?></textarea>
                    </div>
                    <div class="button-container">
                        <button class="action-button" id="copy-btn">
                            <i class="fas fa-copy"></i> 复制内容
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- 处理示例 -->
            <div class="example-section">
                <div class="example-header">
                    <i class="fas fa-lightbulb"></i>
                    <h2>处理示例</h2>
                </div>
                <div class="example-content">
                    <div class="example-row">
                        <div class="example-label">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>输入示例:</span>
                        </div>
                        <div class="example-text">5.30 P@k.CU WMw:/ 04/07 # 出差旅行必备 这个真的住酒店一定要提前备一些，或者睡火车卧铺，那个枕头更要套上这种一次性枕套，便宜好用，小巧便携！# 一次性枕套 # 出差旅行必备 # 好物推荐🔥 https://v.douyin.com/4FJdG6AEPNo/ 复制此链接，打开Dou音搜索，直接观看视频！</div>
                    </div>
                    <div class="example-row">
                        <div class="example-label">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>输出结果:</span>
                        </div>
                        <div class="example-text">#出差旅行必备 这个真的住酒店一定要提前备一些，或者睡火车卧铺，那个枕头更要套上这种一次性枕套，便宜好用，小巧便携！#一次性枕套#出差旅行必备#好物推荐🔥</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 复制成功提示 -->
        <div class="copy-notification" id="copy-notification">
            <i class="fas fa-check-circle"></i> 内容已复制到剪贴板！
        </div>
    </div>

    <script>
        // 复制功能实现
        document.getElementById('copy-btn').addEventListener('click', function() {
            const resultTextarea = document.getElementById('result');
            resultTextarea.select();
            
            try {
                // 使用现代 Clipboard API
                navigator.clipboard.writeText(resultTextarea.value).then(() => {
                    showCopyNotification();
                }).catch(err => {
                    console.error('复制失败:', err);
                    fallbackCopyText(resultTextarea.value);
                });
            } catch (err) {
                // 浏览器不支持 Clipboard API，使用备用方法
                fallbackCopyText(resultTextarea.value);
            }
        });
        
        // 备用复制方法
        function fallbackCopyText(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.opacity = 0;
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showCopyNotification();
                } else {
                    alert('复制失败，请手动复制文本');
                }
            } catch (err) {
                alert('复制失败，请手动复制文本');
            }
            
            document.body.removeChild(textArea);
        }
        
        // 显示复制成功提示
        function showCopyNotification() {
            const notification = document.getElementById('copy-notification');
            notification.classList.add('show');
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 2500);
        }
    </script>
</body>
</html>