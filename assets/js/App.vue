<template>
  <nav class="container navbar navbar-expand-lg navbar-light bg-light">
    <img src="../css/apple-touch-icon.png" width="32" height="32" alt="fruits"
      srcset="https://fruityvice.com/images/apple-touch-icon.png">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item" v-bind:class="{ active: currentPath !== '#/favourite' }">
          <a class="nav-link" href="#/">Fruits</a>
        </li>
        <li class="nav-item" v-bind:class="{ active: currentPath === '#/favourite' }">
          <a class="nav-link" href="#/favourite">Favourites</a>
        </li>
      </ul>
    </div>
  </nav>
  <component :is="currentView" />
</template>

<script>
import Favourites from './components/Favourites.vue'
import Fruits from './components/Fruits.vue'
import NotFound from './components/NotFound.vue'

const routes = {
  '/': Fruits,
  '/favourite': Favourites
}

export default {
  data() {
    return {
      currentPath: window.location.hash
    }
  },
  computed: {
    currentView() {
      return routes[this.currentPath.slice(1) || '/'] || NotFound
    }
  },
  mounted() {
    window.addEventListener('hashchange', () => {
      this.currentPath = window.location.hash
    })
  }
}
</script>