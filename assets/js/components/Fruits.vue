<template>
  <div class="container">
    <div class="h1 text-center py-5">Fruits</div>
    <div class="d-flex flex-row align-items-end" style="gap: 12px;">
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" placeholder="Name" v-model="name">
      </div>
      <div class="form-group">
        <label for="family">Family</label>
        <input type="text" class="form-control" id="family" placeholder="Family" v-model="family">
      </div>
      <div class="form-group">
        <button type="button" class="btn btn-primary" @click="clickFilter">Filter</button>
      </div>
    </div>
    <table class="table table-hover h6">
      <thead>
      <tr>
        <th scope="col">Name</th>
        <th scope="col">Genus</th>
        <th scope="col">Family</th>
        <th scope="col">Order</th>
        <th scope="col">Carbohydrates</th>
        <th scope="col">Protein</th>
        <th scope="col">Fat</th>
        <th scope="col">Calories</th>
        <th scope="col">Sugar</th>
        <th scope="col">Favourite</th>
      </tr>
      </thead>
      <tbody v-for="fruit in fruits">
      <tr>
        <th scope="row">{{ fruit.name }}</th>
        <td>{{ fruit.genus }}</td>
        <td>{{ fruit.family }}</td>
        <td>{{ fruit.order }}</td>
        <td>{{ fruit.carbohydrates }}</td>
        <td>{{ fruit.protein }}</td>
        <td>{{ fruit.fat }}</td>
        <td>{{ fruit.calories }}</td>
        <td>{{ fruit.sugar }}</td>
        <td>
          <button type="button" class="btn btn-primary form-control"
                  @click="addMyFavourite(fruit.id)">
            {{ myFavourites?.includes(fruit.id) ? 'Remove' : 'Add' }}
          </button>
        </td>
      </tr>
      </tbody>
    </table>
    <div>
      <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
          <li class="page-item" @click="goPrevPage(currentPage)" v-bind:class="{ disabled: currentPage == 1 }">
            <a class="page-link">Previous</a>
          </li>
          <li class="page-item" @click="getFruits(currentPage)">
            <a class="page-link">{{ currentPage }}</a>
          </li>
          <li class="page-item" @click="goNextPage(currentPage)" v-bind:class="{ disabled: currentPage == total }">
            <a class="page-link">Next</a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script>
import {apiUrl} from '../constant';
import axios from 'axios';

export default {
  data() {
    return {
      fruits: [],
      currentPage: 1,
      rowsPerPage: 10,
      total: 0,
      myFavourites: [],
      name: '',
      family: '',
    }
  },
  methods: {
    goPrevPage: function (currentPage) {
      if (currentPage > 1) {
        this.currentPage = currentPage - 1;
        this.getFruits(currentPage - 1);
      }
    },
    goNextPage: function (currentPage) {
      if (currentPage < this.total) {
        this.currentPage = currentPage + 1;
        this.getFruits(currentPage + 1);
      }
    },
    getFruits: async function (currentPage) {
      try {
        const params = {
          currentPage: currentPage,
          rowsPerPage: this.rowsPerPage,
        };

        const res = await axios.post(apiUrl + '/fruits', params);
        if (res.data) {
          this.fruits = res.data?.fruits ?? [];
          this.total = Math.ceil(res.data?.total / this.rowsPerPage) ?? 0;
        }
      } catch (error) {
        console.log(error);
      }
    },
    addMyFavourite: async function (fruitId) {
      try {
        if (this.myFavourites.includes(fruitId)) {
          const res = await axios.post(apiUrl + '/addFavourite', {id: fruitId, status: false});
          if (res.data) {
            this.myFavourites = this.myFavourites?.filter(a => a !== fruitId);
          }
        } else {
          if (this.myFavourites?.length < 10) {
            const res = await axios.post(apiUrl + '/addFavourite', {id: fruitId, status: true});
            if (res.data) {
              this.myFavourites = [...this.myFavourites, fruitId];
            }
          }
        }
      } catch (error) {
        console.log(error);
      }
    },
    clickFilter: async function () {
      try {
        const params = {
          currentPage: this.currentPage,
          rowsPerPage: this.rowsPerPage,
          name: this.name,
          family: this.family,
        };

        const res = await axios.post(apiUrl + '/fruits', params);
        this.fruits = res.data?.fruits ?? [];
        this.total = Math.ceil(res.data?.total / this.rowsPerPage) ?? 0;
        this.myFavourites = res.data?.favourites?.map(a => a.id) ?? [];

      } catch (error) {
        console.log(error);
      }
      console.log(this.name, this.family);
    }
  },
  async created() {
    try {
      const params = {
        currentPage: this.currentPage,
        rowsPerPage: this.rowsPerPage,
        name: this.name,
        family: this.family,
      };

      const res = await axios.post(apiUrl + '/fruits', params);
      this.fruits = res.data?.fruits ?? [];
      this.total = Math.ceil(res.data?.total / this.rowsPerPage) ?? 0;
      this.myFavourites = res.data?.favourites?.map(a => a.id) ?? [];
    } catch (error) {
      console.log(error);
    }
  }
}
</script>