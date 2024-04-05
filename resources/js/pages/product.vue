<script setup>
import watch from '@images/eCommerce/1.png'
import keyboard from '@images/eCommerce/10.png'
import show from '@images/eCommerce/11.png'
import ipad from '@images/eCommerce/12.png'
import projector from '@images/eCommerce/14.png'
import bag from '@images/eCommerce/17.png'
import waterPod from '@images/eCommerce/19.png'
import iphone from '@images/eCommerce/2.png'
import watch2 from '@images/eCommerce/25.png'
import vr from '@images/eCommerce/18.png'
import onePlus from '@images/eCommerce/9.png'

definePage({ meta: { layout: 'blank' } })

const searchQuery = ref('')
const selectedRole = ref()
const selectedStatus = ref()
const isMasterDialogVisible = ref(false)
const page = ref(1)
const sortBy = ref()
const orderBy = ref()
const headers = [
  {
    title: 'Name',
    key: 'name',
  },
  {
    title: 'Seller Name',
    key: 'seller',
    sortable: false,
  },
  {
    title: 'Price',
    key: 'price',
    sortable: false,
  },
  {
    title: 'Discount Price',
    key: 'discount_price',
    sortable: false,
  },
  {
    title: 'Quantity',
    key: 'quantity',
    sortable: false,
  },
  {
    title: 'In Stock',
    key: 'in_stock',
    sortable: false,
  },
  {
    title: 'Actions',
    key: 'actions',
    sortable: false,
  },
]

const updateOptions = options => {
  sortBy.value = options.sortBy[0]?.key
  orderBy.value = options.sortBy[0]?.order
}



// Data table options
const itemsPerPage = ref(10)


const {
  data: pricePackagesData,
  execute: fetchPackages,
} = await useApi(createUrl('/products', {
  query: {
    q: searchQuery,
    status: selectedStatus,
    role: selectedRole,
    itemsPerPage,
    page,
    sortBy,
    orderBy,
  },
}))

console.log('pricePackagesData')
console.log(pricePackagesData)

const packages = computed(() => pricePackagesData.value.products.data)
const totalPackages = computed(() => pricePackagesData.value.total_products)


const packageDetails = ref({});
const newPackage = async data => {
  if(data.id !== undefined){
    console.log('here for updating')
    // update customer
    await $api(`/product/${ data.id }`, {
      method: 'PUT',
      body: data,
    })
    isSnackbarTopEndVisible.value = true
    snackBarMsg.value ='Product updated successfully';
  } else {
    console.log('parent')
    console.log(data)
    //post data to the server
    const res = await $api(`/product`, {
      method: 'POST',
      body: data,
    })
    isSnackbarTopEndVisible.value = true
    snackBarMsg.value ='Product created successfully';
  }
  isMasterDialogVisible.value = false
  fetchPackages()
}
const selectedPackage = (item) => {
  packageDetails.value = item
  isMasterDialogVisible.value = true
}

const isSnackbarTopEndVisible = ref(false)
const snackBarMsg = ref('');
const deleteUser = async id => {
  await $api(`/product/${ id }`, { method: 'DELETE' })
  isSnackbarTopEndVisible.value = true
  snackBarMsg.value ='Product deleted successfully';

  // re-fetch products
  fetchPackages()
}


</script>

<template>
  <VSnackbar
    v-model="isSnackbarTopEndVisible"
    location="top end"
    :timeout="2000"
  >
    {{snackBarMsg }}
  </VSnackbar>
  <VContainer>
    <VCardText class="text-center">
      <div class="">
        <h1 class="text-h1">Products</h1>
        <p class="text-subtitle-1">
          Select your desire products from here
        </p>
      </div>
        <VBtn color="warning" class="mb-2" :to="{ name: 'root' }">
          <VIcon color="primary" icon="tabler-world" />
          E-Commerce portal
        </VBtn>
    </VCardText>
    <VCard class="py-4 px-4">

      <VBtn color="success" class="mb-2 float-end"
      @click="isMasterDialogVisible = true">
        <VIcon icon="tabler-plus" />
        Add Product
      </VBtn>

      <!-- SECTION datatable -->
      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:page="page"
        :items="packages"
        :items-length="totalPackages"
        :headers="headers"
        class="text-no-wrap"
        @update:options="updateOptions"
      >
        <!-- User -->
        <template #item.name="{ item }">
          <div class="d-flex align-center gap-x-4">
            <VAvatar
              size="34"
              variant="plain"
              color="danger"
            >
              <VImg
                v-if="item.img_url"
                :src="item.img_url"
              />
              <span v-else>{{ avatarText(item.fullName) }}</span>
            </VAvatar>
            <div class="d-flex flex-column ms-3">
              <span class="d-block font-weight-medium text-high-emphasis text-truncate">{{ item.name }}</span>
              <small>{{ item.note }}</small>
            </div>
          </div>
        </template>

        <!-- 👉 Role -->
        <template #item.in_stock="{ item }">
          <VChip color="success" v-if="item.in_stock === 1">
            Yes
          </VChip>
          <VChip color="info" v-else>
            No
          </VChip>
        </template>


        <!-- Actions -->
        <template #item.actions="{ item }">
          <IconBtn @click="deleteUser(item.id)">
            <VIcon icon="tabler-trash" />
          </IconBtn>

          <IconBtn @click="selectedPackage(item)">
            <VIcon icon="tabler-pencil" />
          </IconBtn>

          <VBtn
            icon
            variant="text"
            color="medium-emphasis"
          >
            <VIcon icon="tabler-dots-vertical" />
            <VMenu activator="parent">
              <VList>

                <VListItem @click="selectedPackage(item)"
                >
                  <template #prepend>
                    <VIcon icon="tabler-pencil" />
                  </template>
                  <VListItemTitle>Edit</VListItemTitle>
                </VListItem>

                <VListItem @click="deleteUser(item.id)">
                  <template #prepend>
                    <VIcon icon="tabler-trash" />
                  </template>
                  <VListItemTitle>Delete</VListItemTitle>
                </VListItem>
              </VList>
            </VMenu>
          </VBtn>
        </template>

        <!-- pagination -->
        <template #bottom>
          <TablePagination
            v-model:page="page"
            :items-per-page="itemsPerPage"
            :total-items="totalPackages"
          />
        </template>
      </VDataTableServer>
      <!-- SECTION -->
    </VCard>
    <!-- 👉 Add New User -->
    <!-- 👉 add Address dialog -->
    <AddEditProductDialog
      v-model:isDialogVisible="isMasterDialogVisible"
      :product-details="packageDetails"
      @submit="newPackage"
    />
  </VContainer>
</template>

<style lang="scss">
.checkout-card {
  margin-block: 10.5rem 5.25rem;
}

@media (max-width: 960px) and (min-width: 600px) {
  .checkout-page {
    .v-container {
      padding-inline: 2rem !important;
    }
  }
}

@media (max-width: 600px) {
  .checkout-card {
    margin-block-start: 6rem;
  }
}
</style>
