async function fetchMovieData(imdbID) {
  const apiKey = 'ef9abe01';
  const url = `https://www.omdbapi.com/?i=${imdbID}&apikey=${apiKey}`;

  try {
    const response = await fetch(url);
    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Error fetching movie data:', error);
    return null;
  }
}

async function fetchTopRatedMovies() {
  const topRatedIMDbIDs = [
    'tt0111161', 'tt0068646', 'tt0071562', 'tt0468569', 'tt0050083',
    'tt0108052', 'tt0167260', 'tt0110912', 'tt0120737', 'tt0137523',
  ];

  const moviePromises = topRatedIMDbIDs.map(fetchMovieData);
  const movies = await Promise.all(moviePromises);
  return movies;
}

function createCarouselItems(movies) {
  const carousel = document.querySelector('.slick-carousel');

  movies.forEach((movie) => {
      const movieCard = `
          <div class="col-md-2">
              <div class="card">
                  <img src="${movie.Poster}" class="card-img-top" alt="${movie.Title}">
                  <div class="card-body">
                      <h6 class="card-title">${movie.Title}</h6>
                      <p class="card-text">${movie.Plot.substring(0,70)}...</p>
                      <p><strong>Rating:</strong> ${movie.imdbRating}</p>
                    </div>
                  </div>
                </div>
          `;
          carousel.insertAdjacentHTML('beforeend', movieCard);
      });
  
      $(carousel).slick({
          slidesToShow: 5,
          slidesToScroll: 1,
          prevArrow: '<button type="button" class="slick-prev">Previous</button>',
          nextArrow: '<button type="button" class="slick-next">Next</button>',
          responsive: [
              {
                  breakpoint: 1024,
                  settings: {
                      slidesToShow: 3,
                      slidesToScroll: 1,
                  },
              },
              {
                  breakpoint: 600,
                  settings: {
                      slidesToShow: 2,
                      slidesToScroll: 1,
                  },
              },
              {
                  breakpoint: 480,
                  settings: {
                      slidesToShow: 1,
                      slidesToScroll: 1,
                  },
              },
          ],
      });
  }
  
  (async function init() {
      const movies = await fetchTopRatedMovies();
      createCarouselItems(movies);
})();
