document.addEventListener('DOMContentLoaded', function() {
    const container = document.createElement('div');
    container.className = 'fixed top-0 left-0 p-4 z-50 flex items-center';

    const logoImg = document.createElement('img');
    logoImg.src = 'https://i.ibb.co/M1cybFS/skl.png';
    logoImg.alt = 'Sangguniang Kabataan';
    logoImg.style.width = '80px'; 

    const heading = document.createElement('h1');
    heading.textContent = 'SK E-Voting System';
    heading.className = 'text-2xl text-gray-700 ml-4';

    container.appendChild(logoImg);
    container.appendChild(heading);

    document.body.appendChild(container);
});
