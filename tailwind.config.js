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
        lg: '4rem',
      },
      screens: {
        sm: '640px',
        md: '768px',
        lg: '1024px',
        xl: '1280px',
        '2xl': '1536px'
      },
    },
    extend: {
      fontFamily: {
        sans: ['montserrat', 'system-ui', 'sans-serif'],
        heading: ['montserrat', 'system-ui', 'sans-serif'],
      },
      colors: {
        primary: {
        },
        secondary: { 
        },
      },
    },
  },
  plugins: [],
}

