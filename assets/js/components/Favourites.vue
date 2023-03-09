<template>
  <div class="container">
    <div class="h1 text-center py-5">My Favourite Fruits</div>
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
        <th scope="col">(Sum)</th>
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
        <th scope="row">{{ fruit.total }}</th>
      </tr>
      </tbody>
    </table>
  </div>
</template>


<script>
import {apiUrl} from '../constant';
import axios from 'axios';

export default {
  data() {
    return {
      fruits: [],
    }
  },
  async created() {
    try {
      const res = await axios.post(apiUrl + '/favourites');
      this.fruits = res.data?.map(a => ({
        ...a,
        total: a?.carbohydrates ?? 0 + a?.protein ?? 0 + a?.calories ?? 0 + a?.fat ?? 0 + a?.sugar ?? 0
      })) ?? [];
    } catch (error) {
      console.log(error);
    }
  }
}
</script>