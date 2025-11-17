   // webpack.config.js
   const path = require('path');
   const MiniCssExtractPlugin = require('mini-css-extract-plugin');


   module.exports = {
       entry: './src/js/index.js', // Entry point
       output: {
           path: path.resolve(__dirname, 'dist'), // Output directory
           filename: 'app.js', // Output file name
           clean: true // Clean dist folder before each build
       },
       plugins: [
           new MiniCssExtractPlugin({
               filename: 'app.css'
           })
       ],
       devServer: {
           hot: true,
           static: './dist',
           // Only watch source files, exclude dist and node_modules
           watchFiles: [
               'src/**/*.php',
               'src/**/*.css',
               'src/**/*.js',
               'templates/**/*.php',
               '*.php'
           ],
           allowedHosts: 'all',
           host: 'localhost',
           devMiddleware: {
            writeToDisk: true // Add this to ensure files are written to disk
        }
       },
       // Exclude problematic directories
       watchOptions: {
           ignored: [
               '**/node_modules/**',
               '**/dist/**',
               '**/.git/**'
           ],
           poll: 1000, // Check for changes every second
           aggregateTimeout: 300 // Wait 300ms after change before rebuilding
       },

       module: {
        rules: [
            // CSS Processing for source files
            {
                test: /\.css$/,
                exclude: /node_modules/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: { url: false }
                    },
                    {
                        loader: 'postcss-loader',
                        options: {
                            postcssOptions: { url: false }
                        }
                    }
                ]
            },

            // CSS Processing for node_modules
            {
                test: /\.css$/,
                include: /node_modules/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: {
                            url: false,
                            import: false
                        }
                    }
                ]
            },

            // Asset Handling
            {
                test: /\.(png|svg|jpg|jpeg|gif)$/i,
                type: 'asset/resource',
                generator: { emit: false }
            }
        ]
    },
       mode: 'development', // Development or Production
   };
