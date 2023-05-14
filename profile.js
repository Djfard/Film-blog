const topTenFilms = [{
        title: 'The Shawshank Redemption',
        imdbID: 'tt0111161'
    },
    {
        title: 'The Godfather',
        imdbID: 'tt0068646'
    },
    {
        title: 'The Godfather: Part II',
        imdbID: 'tt0071562'
    },
    {
        title: 'The Dark Knight',
        imdbID: 'tt0468569'
    },
    {
        title: '12 Angry Men',
        imdbID: 'tt0050083'
    },
    {
        title: "Schindler's List",
        imdbID: 'tt0108052'
    },
    {
        title: 'The Lord of the Rings: The Return of the King',
        imdbID: 'tt0167260'
    },
    {
        title: 'Pulp Fiction',
        imdbID: 'tt0110912'
    },
    {
        title: 'The Lord of the Rings: The Fellowship of the Ring',
        imdbID: 'tt0120737'
    },
    {
        title: 'Fight Club',
        imdbID: 'tt0137523'
    },
];

const filmSelect = document.getElementById('review-film');

topTenFilms.forEach(film => {
    const option = document.createElement('option');
    option.value = film.imdbID;
    option.text = film.title;
    filmSelect.add(option);
});

filmSelect.addEventListener('change', function () {
    document.getElementById('review-film-name').value = this.options[this.selectedIndex].text;
});