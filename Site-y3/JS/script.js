async function fetchTopRatedMovies() {


    try {
        const response = await fetch();
        const data = await response.json();
        return data.results.slice(0, 5);
      } catch (error) {
        console.error('Error fetching top-rated movies:', error);
        return [];
      }
}