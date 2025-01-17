   // webpack.config.js
   const path = require('path');


   module.exports = {
       entry: './src/js/index.js', // Entry point
       output: {
           path: path.resolve(__dirname, 'dist'), // Output directory
           filename: 'app.js', // Output file name
           clean: true // This will clean the dist folder before each build
       },
       devServer: {
           hot: true,
           static: './dist',
           watchFiles: ['**/*.php', '**/*.css', '**/*.js'],
           allowedHosts: 'all',
           host: 'localhost',
           devMiddleware: {
            writeToDisk: true // Add this to ensure files are written to disk
        }
       },
       
       module: {
        rules: [
            {
                test: /\.css$/,
                use: ['style-loader', 'css-loader', 'postcss-loader']
            }
        ]
    },
       mode: 'development', // Set mode to development or production
   };
