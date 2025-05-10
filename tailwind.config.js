/** @type {import('tailwindcss').Config} */

module.exports = {
  content: [
    "./**/*.{php,js}",
    "!./node_modules/**/*"
  ],
  theme: {
    container: {
      center: true,
      padding: {
        DEFAULT: '1rem',
        md: '2rem',
        xl: '4rem',
      },
      screens: {
        sm: '640px',
        md: '768px',
        lg: '1024px',
        xl: '1280px',
        '2xl': '1500px'
      },
    },
    extend: {
      fontFamily: {
        head: ['montserrat', 'system-ui', 'sans-serif'],
        base: ['montserrat', 'system-ui', 'sans-serif'],
      },
      colors: {
      },
    },
  },
  plugins: [],
}

