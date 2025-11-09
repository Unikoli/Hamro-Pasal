<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bazaar Buddy - Inventory Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        metallic: {
                            light: '#eef2f3',
                            mid: '#c5d1d5',
                            dark: '#8a9ea5',
                            gold: '#d4af37',
                        },
                        steel: {
                            100: '#f0f4f5',
                            800: '#2d3748',
                            900: '#1a202c',
                        }
                    },
                    boxShadow: {
                        'metallic': '0 10px 30px -5px rgba(0, 0, 0, 0.5), inset 0 0 15px rgba(255, 255, 255, 0.3)',
                        'metallic-btn': '0 5px 15px rgba(0, 0, 0, 0.4), inset 0 0 10px rgba(255, 255, 255, 0.3)',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #2c3e50 0%, #1a1a2e 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }
        
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 10% 20%, rgba(212, 175, 55, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(212, 175, 55, 0.1) 0%, transparent 20%);
            z-index: -1;
        }
        
        .metallic-text {
            background: linear-gradient(to bottom, #eef2f3, #8a9ea5);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .logo-container {
            position: relative;
            display: inline-block;
        }
        
        .logo-container::after {
            content: "";
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            background: linear-gradient(145deg, #1a202c, #2d3748);
            border-radius: 50%;
            z-index: -1;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.5);
        }
        
        .metallic-btn {
            background: linear-gradient(145deg, #3a4758, #2a3444);
            border: 2px solid;
            border-image: linear-gradient(to bottom, #d4af37, #8e6d28) 1;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .metallic-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
        
        .metallic-btn::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        
        .metallic-btn:hover::before {
            left: 100%;
        }
        
        .pulse {
            animation: pulse 3s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body class="text-gray-100 flex items-center justify-center">
    <div class="container mx-auto px-4 py-12 flex flex-col items-center">
        <!-- Main Logo -->
        <div class="logo-container pulse mb-8">
            <div class="bg-gradient-to-br from-metallic.light via-metallic.mid to-metallic.dark rounded-full p-8 shadow-metallic">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-40 w-40" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#1a202c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 17L12 22L22 17" stroke="#1a202c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 12L12 17L22 12" stroke="#1a202c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 12V17" stroke="#1a202c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
        
        <!-- App Name -->
        <h1 class="text-6xl md:text-7xl font-bold mb-6 metallic-text font-orbitron tracking-tighter">
            HAMRO PASAL
        </h1>
        
        <!-- Tagline -->
        <p class="text-xl text-metallic.mid mb-12 max-w-2xl text-center font-light tracking-wide">
            INDUSTRIAL-GRADE INVENTORY MANAGEMENT FOR MODERN RETAIL
        </p>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-6 mb-16">
            <a href="{{ route('login') }}" class="metallic-btn px-10 py-4 text-lg font-bold rounded-lg text-metallic.light hover:text-white transition-colors duration-300">
                LOG IN
            </a>
            <a href="{{ route('register') }}" class="metallic-btn px-10 py-4 text-lg font-bold rounded-lg bg-gradient-to-r from-metallic.gold/20 to-metallic.gold/10 text-metallic.gold border-metallic.gold hover:text-yellow-200 transition-colors duration-300">
                REGISTER
            </a>
        </div>
        
        <!-- Feature Highlights -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mt-8">
            <div class="bg-steel-800/50 backdrop-blur-sm p-6 rounded-xl border border-steel-700">
                <div class="text-metallic.gold text-3xl mb-4">📊</div>
                <h3 class="text-xl font-bold text-metallic.mid mb-2">Real-Time Analytics</h3>
                <p class="text-steel-100">Track sales, inventory levels, and trends with precision.</p>
            </div>
            
            <div class="bg-steel-800/50 backdrop-blur-sm p-6 rounded-xl border border-steel-700">
                <div class="text-metallic.gold text-3xl mb-4">🤖</div>
                <h3 class="text-xl font-bold text-metallic.mid mb-2">AI Forecasting</h3>
                <p class="text-steel-100">Predict demand and optimize stock with machine learning.</p>
            </div>
            
            <div class="bg-steel-800/50 backdrop-blur-sm p-6 rounded-xl border border-steel-700">
                <div class="text-metallic.gold text-3xl mb-4">🔔</div>
                <h3 class="text-xl font-bold text-metallic.mid mb-2">Smart Alerts</h3>
                <p class="text-steel-100">Get notified about low stock, anomalies, and opportunities.</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="mt-16 text-center text-steel-100 text-sm">
            <p>© {{ date('Y') }} Hamro Pasal. All rights reserved.</p>
        </div>
    </div>
</body>
</html>