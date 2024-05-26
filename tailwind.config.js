/** @type {import('tailwindcss').Config} */

module.exports = {
  content: [
    './frontend/*.html',
    './backend/*.php',
    './index.html',
    './backend/js/*.js'
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
    require('@tailwindcss/container-queries'),
    require('daisyui'),
  ],
  daisyui: {
    themes: [
      "light",
      "dark",
      "dracula",
    ],
  },
  darkMode: 'class',
  theme: {
    extend: {}
  },
}