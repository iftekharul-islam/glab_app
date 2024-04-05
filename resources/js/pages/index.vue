<script setup>
import AddressContent from '@/views/wizard-examples/checkout/Address.vue'
import CartContent from '@/views/wizard-examples/checkout/Cart.vue'
import ConfirmationContent from '@/views/wizard-examples/checkout/Confirmation.vue'
import PaymentContent from '@/views/wizard-examples/checkout/Payment.vue'
import googleHome from '@images/pages/google-home.png'
import iphone11 from '@images/pages/iphone-11.png'
import headphone from '@images/pages/custom-radio-img-3.png'
import customAddress from '@images/svg/address.svg'
import customCart from '@images/svg/cart.svg'
import customPayment from '@images/svg/payment.svg'
import customTrending from '@images/svg/trending.svg'

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

const { data: fetchProducts } = await useApi('/all-products');
const allPackages = ref(fetchProducts.value.products)

console.log('allPackages')
console.log(allPackages.value)

const cartItems = allPackages.value

// const cartItems = [
//   {
//     id: 1,
//     name: 'Google - Google Home - White',
//     seller: 'Google',
//     inStock: true,
//     rating: 4,
//     price: 299,
//     discountPrice: 359,
//     image: googleHome,
//     quantity: 1,
//     estimatedDelivery: '18th Nov 2021',
//   },
//   {
//     id: 2,
//     name: 'Apple iPhone 11 (64GB, Black)',
//     seller: 'Apple',
//     inStock: true,
//     rating: 4,
//     price: 899,
//     discountPrice: 999,
//     image: iphone11,
//     quantity: 1,
//     estimatedDelivery: '20th Nov 2021',
//   },
//   {
//     id: 3,
//     name: 'Apple airpods (White)',
//     seller: 'Apple',
//     inStock: true,
//     rating: 4,
//     price: 299,
//     discountPrice: 599,
//     image: headphone,
//     quantity: 1,
//     estimatedDelivery: '18th Nov 2021',
//   },
//   {
//     id: 3,
//     name: 'Apple airpods (White)',
//     seller: 'Apple',
//     inStock: true,
//     rating: 4,
//     price: 299,
//     discountPrice: 599,
//     image: headphone,
//     quantity: 1,
//     estimatedDelivery: '18th Nov 2021',
//   }
// ]


const checkoutSteps = [
  {
    title: 'Cart',
    icon: customCart,
  },
  {
    title: 'Address',
    icon: customAddress,
  },
  {
    title: 'Payment',
    icon: customPayment,
  },
  {
    title: 'Confirmation',
    icon: customTrending,
  },
]
const cleanCheckout = ref({
  cartItems: [],
  promoCode: 'Hello',
  orderAmount: 1198,
  deliveryAddress: 'home',
  deliverySpeed: 'free',
  deliveryCharges: 0,
  addresses: [
    {
      title: 'John Doe (Default)',
      desc: '4135 Parkway Street, Los Angeles, CA, 90017',
      subtitle: '1234567890',
      value: 'home',
    },
    {
      title: 'ACME Inc.',
      desc: '87 Hoffman Avenue, New York, NY, 10016',
      subtitle: '1234567890',
      value: 'office',
    },
  ],
})
const checkoutData = ref({
  cartItems: [],
  promoCode: 'Hello',
  orderAmount: 1198,
  deliveryAddress: 'home',
  deliverySpeed: 'free',
  deliveryCharges: 0,
  addresses: [
    {
      title: 'John Doe (Default)',
      desc: '4135 Parkway Street, Los Angeles, CA, 90017',
      subtitle: '1234567890',
      value: 'home',
    },
    {
      title: 'ACME Inc.',
      desc: '87 Hoffman Avenue, New York, NY, 10016',
      subtitle: '1234567890',
      value: 'office',
    },
  ],
})

const addItemToCheckout = (item) => {
  const existingItem = checkoutData.value.cartItems.find(cartItem => cartItem.id === item.id);
  if (existingItem) {
    existingItem.quantity++;
  } else {
    checkoutData.value.cartItems.push({ ...item, quantity: 1 });
  }
  updateTotalItems();
}

const updateTotalItems = () => {
  totalItems.value = parseInt(checkoutData.value.cartItems.reduce((acc, item) => acc + item.quantity, 0));
}

const totalItems = ref(0);

const viewCheckout = ref(false);
const removeItem = item => {
  checkoutData.value.cartItems = checkoutData.value.cartItems.filter(i => i.id !== item.id)
}

const updatedData = data => {
  console.log('Data updated on parent function', data)
  checkoutData.value = data
  updateTotalItems();
}

const updateViewCheckout = (value) => {
  console.log('View checkout updated on parent function', value)
  viewCheckout.value = value
  currentStep.value = 0
}

const emptyViewCheckout = () => {
  console.log('View checkout updated on parent function')
  checkoutData.value = cleanCheckout.value
  currentStep.value = 0
  viewCheckout.value = false
  updateTotalItems();
}


const isSnackbarTopEndVisible = ref(false);

const actionToCheckout = () => {
  if(!checkoutData.value.cartItems.length) {
    isSnackbarTopEndVisible.value = true
    return
  }
  viewCheckout.value = true
}

const currentStep = ref(0)
</script>

<template>
  <VSnackbar
    v-model="isSnackbarTopEndVisible"
    location="top end"
    :timeout="2000"
  >
    Cart item is empty . Please add product to move forward
  </VSnackbar>
    <VContainer v-if="viewCheckout === false">
      <VCardText class="text-center">
        <h1 class="text-h1">Products</h1>
        <p class="text-subtitle-1">
          Select your desire products from here
        </p>
        <VBadge
          @click="actionToCheckout"
          :content="totalItems"
          bordered
          color="error"
        >
          <VBtn color="info">
            <VIcon icon="tabler-shopping-cart-plus" />
          </VBtn>
        </VBadge>
        <VBtn class="ml-5" color="warning" :to="{ name: 'product' }">
          Go To Inventory
        </VBtn>
      </VCardText>

      <!-- 👉 Cart items -->
      <div
        v-if="cartItems.length"
      >
        <VRow>
          <template v-for="(item, index) in cartItems" :key="item.name" >
            <VCol
              col="2" class="mx-1 my-1">
              <VCard>
                <div class="d-flex align-center pa-3 position-relative flex-column flex-sm-column">

                  <div>
                    <VImg
                      width="140"
                      :src="item.img_url"
                      height="90px"
                    />
                  </div>

                  <div class="d-flex w-100 flex-column flex-md-row">
                    <div class="d-flex flex-column gap-y-2">
                      <h6 class="text-h6">
                        {{ item.name }}
                      </h6>
                      <div class="d-flex align-center text-no-wrap gap-4 text-body-1">
                        <div class="text-disabled">
                          Sold by:
                          <span class="d-inline-block text-primary">  {{ item.seller }}</span>
                        </div>
                        <VChip
                          :color="item.in_stock ? 'success' : 'error'"
                          label
                          size="small"
                        >
                          {{ item.in_stock ? 'In Stock' : 'Out of Stock' }}
                        </VChip>
                      </div>

                      <VRating
                        density="compact"
                        :model-value="item.rating"
                        size="24"
                        readonly
                      />
                    </div>

                    <VSpacer />

                    <div
                      class="d-flex flex-column mt-5 text-start text-md-end"
                      :class="$vuetify.display.mdAndDown ? 'gap-2' : 'gap-4'"
                    >
                      <div class="d-flex text-base align-self-md-end">
                        <div class="text-primary">
                          ${{ item.price }}
                        </div>
                        <div>/</div>
                        <div class="text-decoration-line-through">
                          ${{ item.discount_price }}
                        </div>
                      </div>

                      <div>
                        <VBtn @click="addItemToCheckout(item)">
                          <VIcon icon="tabler-shopping-cart-plus" />
                          <span class="ms-2">Add to cart</span>
                        </VBtn>

                      </div>
                    </div>
                  </div>
                </div>
              </VCard>
            </VCol>

          </template>
        </VRow>
      </div>
      <VCardText class="text-center mt-4">
        <VBtn color="success" @click="actionToCheckout" class="ml-2">
          Proceed to checkout
        </VBtn>
      </VCardText>
    </VContainer>
    <VContainer v-if="viewCheckout === true">
      <VCardText class="text-center">
        <h1 class="text-h1">Checkout</h1>
        <p class="text-subtitle-1">
          Review your order before proceeding
        </p>
      </VCardText>
        <VCard>
          <VCardText>
            <!-- 👉 Stepper -->
            <AppStepper
              :is-active-step-valid="false"
              v-model:current-step="currentStep"
              class="checkout-stepper"
              :items="checkoutSteps"
              :direction="$vuetify.display.mdAndUp ? 'horizontal' : 'vertical'"
              align="center"
            />
          </VCardText>
          <VDivider />
          <VCardText>
            <!-- 👉 stepper content -->
            <VWindow
              v-model="currentStep"
              class="disable-tab-transition"
              :touch="false"
            >
              <VWindowItem>
                <CartContent
                  v-model:current-step="currentStep"
                  v-model:checkout-data="checkoutData"
                  @update:checkout-data="updatedData"
                  @update:view-checkout="updateViewCheckout"
                />
              </VWindowItem>

              <VWindowItem>
                <AddressContent
                  v-model:current-step="currentStep"
                  v-model:checkout-data="checkoutData"
                  @update:view-checkout="updateViewCheckout"
                />
              </VWindowItem>
              <VWindowItem>
                <PaymentContent
                  v-model:current-step="currentStep"
                  v-model:checkout-data="checkoutData"
                />
              </VWindowItem>
              <VWindowItem>
                <ConfirmationContent
                  v-model:checkout-data="checkoutData"
                   @update:view-checkout="emptyViewCheckout"
                />
              </VWindowItem>
            </VWindow>
          </VCardText>
        </VCard>
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
