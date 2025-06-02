/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    "./**/*.{html,js,php}",
  ],
  theme: {
    extend: {
      colors: {
        'blue-light': '#f8fcfc',
        'blue-dark': '#1a1b4b',
      },
    },
  },
  plugins: [],
} 