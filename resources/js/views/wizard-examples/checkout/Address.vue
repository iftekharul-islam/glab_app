<script setup>
const props = defineProps({
  currentStep: {
    type: Number,
    required: false,
  },
  checkoutData: {
    type: null,
    required: true,
  },
})

const emit = defineEmits([
  'update:currentStep',
  'update:checkout-data',
  'update:view-checkout',
])

const checkoutAddressDataLocal = ref(props.checkoutData)
const isEditAddressDialogVisible = ref(false)

const deliveryOptions = [
  {
    icon: { icon: 'tabler-user' },
    title: 'Standard',
    desc: 'Get your product in 1 Week.',
    value: 'free',
  },
  {
    icon: { icon: 'tabler-star' },
    title: 'Express',
    desc: 'Get your product in 4 days.',
    value: 'express',
  },
  {
    icon: { icon: 'tabler-crown' },
    title: 'Overnight',
    desc: 'Get your product in 1 day.',
    value: 'overnight',
  },
]

const resolveAddressBadgeColor = {
  home: 'primary',
  office: 'success',
}

const resolveDeliveryBadgeData = {
  free: {
    color: 'success',
    price: 'Free',
  },
  express: {
    color: 'secondary',
    price: 10,
  },
  overnight: {
    color: 'secondary',
    price: 15,
  },
}

const totalPriceWithDeliveryCharges = computed(() => {
  checkoutAddressDataLocal.value.deliveryCharges = 0
  if (checkoutAddressDataLocal.value.deliverySpeed !== 'free')
    checkoutAddressDataLocal.value.deliveryCharges = resolveDeliveryBadgeData[checkoutAddressDataLocal.value.deliverySpeed].price
  
  return checkoutAddressDataLocal.value.orderAmount + checkoutAddressDataLocal.value.deliveryCharges
})

const updateAddressData = () => {
  emit('update:checkout-data', checkoutAddressDataLocal.value)
}

const nextStep = () => {
  updateAddressData()
  emit('update:currentStep', props.currentStep ? props.currentStep + 1 : 1)
}

const sendToPrevious = () => {
  emit('update:currentStep', props.currentStep - 1)
}

watch(() => props.currentStep, updateAddressData)
</script>

<template>
  <VRow>
    <VCol
      cols="12"
      md="8"
    >
      <!-- 👉 Address options -->
      <h6 class="text-h6 mb-4">
        Select your preferable address
      </h6>

      <!-- 👉 Address custom input -->
      <CustomRadios
        v-model:selected-radio="checkoutAddressDataLocal.deliveryAddress"
        :radio-content="checkoutAddressDataLocal.addresses"
        :grid-column="{ cols: '12', sm: '6' }"
      >
        <template #default="{ item }">
          <div class="w-100">
            <div class="d-flex justify-space-between mb-3">
              <h6 class="text-base font-weight-medium">
                {{ item.title }}
              </h6>

              <VChip
                :color="resolveAddressBadgeColor[item.value]"
                label
                size="small"
                class="text-capitalize"
              >
                {{ item.value }}
              </VChip>
            </div>

            <p class="mb-0 text-sm">
              {{ item.desc }}
            </p>
            <p class="text-sm mb-3">
              Mobile: {{ item.subtitle }}
            </p>
          </div>
        </template>
      </CustomRadios>

      <!-- 👉 Delivery options -->
      <h6 class="text-h6 my-4">
        Choose Delivery Speed
      </h6>

      <!-- 👉 Delivery options custom input -->
      <CustomRadiosWithIcon
        v-model:selected-radio="checkoutAddressDataLocal.deliverySpeed"
        :radio-content="deliveryOptions"
        :grid-column="{ cols: '12', sm: '4' }"
      >
        <template #default="{ item }">
          <div class="d-flex flex-column align-center gap-2 w-100">
            <div class="d-flex justify-end w-100 mb-n3">
              <VChip
                :color="resolveDeliveryBadgeData[item.value].color"
                size="small"
                label
              >
                {{
                  resolveDeliveryBadgeData[item.value].price === 'Free'
                    ? resolveDeliveryBadgeData[item.value].price : `$${resolveDeliveryBadgeData[item.value].price}`
                }}
              </VChip>
            </div>

            <VIcon
              v-bind="item.icon"
              size="28"
            />

            <h6 class="text-h6">
              {{ item.title }}
            </h6>
            <p class="text-sm text-center mb-0">
              {{ item.desc }}
            </p>
          </div>
        </template>
      </CustomRadiosWithIcon>
      <VRow>
        <VCol
          cols="4"
          md="4">
          <a
            class="d-flex align-center justify-space-between rounded py-2 px-5 text-base mt-4"
            style="border: 1px solid rgb(var(--v-theme-primary));"
            @click="emit('update:view-checkout', false)"
          >
            <VIcon
              icon="tabler-arrow-left"
              size="16"
              class="flip-in-rtl text-primary"
            />
            Go to Products list
          </a>
        </VCol>
      </VRow>
    </VCol>

    <VCol
      cols="12"
      md="4"
    >
      <VCard
        flat
        variant="outlined"
      >
        <!-- 👉 Delivery estimate date -->
        <VCardText>
          <h6 class="text-h6 mb-4">
            Estimated Delivery Date
          </h6>

          <VList class="card-list">
            <VListItem
              v-for="product in checkoutAddressDataLocal.cartItems"
              :key="product.name"
            >
              <template #prepend>
                <img
                  height="70"
                  width="60"
                  :src="product.img_url"
                  class="me-4"
                >
              </template>

              <div class="text-body-1">
                {{ product.name }}
              </div>
              <h6 class="text-h6 text-medium-emphasis">
                {{ product.estimatedDelivery }}
              </h6>
            </VListItem>
          </VList>
        </VCardText>

        <VDivider />

        <!-- 👉 Price details -->
        <VCardText>
          <h6 class="text-h6 mb-4">
            Price Details
          </h6>

          <div class="d-flex align-center justify-space-between mb-2">
            <span class="text-high-emphasis">Order Total</span>
            <span>${{ checkoutAddressDataLocal.orderAmount }}</span>
          </div>

          <div class="d-flex align-center justify-space-between">
            <span class="text-high-emphasis">Delivery Charges</span>
            <div class="text-end">
              <div
                v-if="checkoutAddressDataLocal.deliverySpeed === 'free'"
                class="d-flex align-center"
              >
                <div class="text-decoration-line-through text-disabled me-2">
                  $5.00
                </div>
                <VChip
                  size="small"
                  color="success"
                >
                  FREE
                </VChip>
              </div>
              <span v-else>${{ resolveDeliveryBadgeData[checkoutAddressDataLocal.deliverySpeed ].price }}.00</span>
            </div>
          </div>
        </VCardText>

        <VDivider />

        <VCardText class="d-flex align-center justify-space-between text-high-emphasis">
          <span class="text-base font-weight-medium">Total</span>
          <span class="text-base font-weight-medium">
            ${{ totalPriceWithDeliveryCharges }}
          </span>
        </VCardText>
      </VCard>
      <VBtn
        color="warning"
        class="mt-4 mr-2"
        @click="sendToPrevious"
      >
      Previous
      </VBtn>
      <VBtn
        class="mt-4"
        @click="nextStep"
      >
        Place Order
      </VBtn>
    </VCol>
  </VRow>
  <AddEditAddressDialog v-model:isDialogVisible="isEditAddressDialogVisible" />
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 1rem;
}
</style>
