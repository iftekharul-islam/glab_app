<script setup>

import appleWatch from '@images/eCommerce/1.png'
import keyboard from '@images/eCommerce/10.png'
import shoe from '@images/eCommerce/11.png'
import ipad from '@images/eCommerce/12.png'
import projector from '@images/eCommerce/14.png'
import bag from '@images/eCommerce/17.png'
import waterPod from '@images/eCommerce/19.png'
import iphone from '@images/eCommerce/2.png'
import watch2 from '@images/eCommerce/25.png'
import vr from '@images/eCommerce/18.png'
import onePlus from '@images/eCommerce/9.png'

const props = defineProps({
  productDetails: {
    type: Object,
    required: false,
    default: () => ({
      name: '',
      seller: '',
      img_url: '',
      price: '',
      discount_price: '',
      quantity: '',
      in_stock: 1,
    }),
  },
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
})

const isFormValid = ref(false)

const emit = defineEmits([
  'update:isDialogVisible',
  'submit',
])

console.log(props.productDetails)
const productDetails = ref(structuredClone(toRaw(props.productDetails)))

console.log('productDetails.value 1')
console.log(productDetails)

const resetForm = () => {
  emit('update:isDialogVisible', false)
  productDetails.value = structuredClone(toRaw(props.productDetails))
}

const capitalizedLabel = (label) => {
  // console.log(label)
  //
  // const convertLabelText = label.toString()
  //
  // return convertLabelText.charAt(0).toUpperCase() + convertLabelText.slice(1)
}

const isSnackbarTopEndVisible = ref(false)
const snackBarMsg = ref('')
const refForm = ref()
const onFormSubmit = () => {
  refForm.value?.validate().then(({valid}) => {
    if (valid) {
      emit('update:isDialogVisible', false)
      emit('submit', productDetails.value)
      nextTick(() => {
        refForm.value?.reset()
        refForm.value?.resetValidation()
        isSnackbarTopEndVisible.value = true
        snackBarMsg.value = 'Product added successfully'
      })
    }
  })
}


watch(props, () => {
  productDetails.value = structuredClone(toRaw(props.productDetails))
  console.log('packageDetails.value 2')
  console.log(productDetails.value)
})

const images = [
  {
    value: appleWatch,
    title: 'Apple Watch 1',
  },
  {
    value: keyboard,
    title: 'Keyboard',
  },
  {
    value: shoe,
    title: 'Show',
  },
  {
    value: ipad,
    title: 'Ipad',
  },
  {
    value: projector,
    title: 'Projector',
  },
  {
    value: bag,
    title: 'Bag',
  },
  {
    value: waterPod,
    title: 'Water Pod',
  },
  {
    value: iphone,
    title: 'Iphone 11 ',
  },
  {
    value: watch2,
    title: 'Apple Watch 2',
  },
  {
    value: vr,
    title: 'VR',
  },
  {
    value: onePlus,
    title: 'One plus mobile',
  },
]

</script>

<template>
  <VSnackbar
    v-model="isSnackbarTopEndVisible"
    location="top end"
    :timeout="2000"
  >
    {{ snackBarMsg }}
  </VSnackbar>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 600 "
    :model-value="props.isDialogVisible"
    @update:model-value="val => $emit('update:isDialogVisible', val)"
  >
    <!-- 👉 Dialog close btn -->
    <DialogCloseBtn @click="$emit('update:isDialogVisible', false)" />

    <VCard class="pa-sm-10 pa-2" >
      <VCardText>
        <!-- 👉 Title -->
        <h4 class="text-h4 text-center mb-2">
          Add New Product
        </h4>
        <p class="text-body-1 text-center mb-6">
          Add new address for express delivery
        </p>

        <!-- 👉 Form -->
        <VForm
          ref="refForm"
          v-model="isFormValid"
          @submit.prevent="onFormSubmit">
          <VRow>
            <!-- 👉 First Name -->
            <VCol
              cols="12"
            >
              <AppTextField
                v-model="productDetails.name"
                label="Product Name"
                :rules="[requiredValidator]"
                placeholder="Apple airpoed"
              />
            </VCol>

            <!-- 👉 Last Name -->
            <VCol
              cols="12"
            >
              <AppTextField
                v-model="productDetails.seller"
                label="Seller name"
                :rules="[requiredValidator]"
                placeholder="Apple"
              />
            </VCol>

            <!-- 👉 Last Name -->
            <VCol
              cols="12"
              md="6"
            >
              <VCheckbox
                v-model="productDetails.in_stock"
                :true-value="1"
                :false-value="0"
                label="Available In Stock"
              />
            </VCol>

            <!-- 👉 Quantity -->
            <VCol
              cols="6"
            >
              <AppTextField
                type="number"
                v-model="productDetails.quantity"
                label="Quantity"
                :rules="[requiredValidator]"
                placeholder="Enter quantity"
                searchable
              />
            </VCol>

            <!-- 👉 Select image -->
            <VCol
              cols="12"
              md="12">
              <AppSelect
                v-model="productDetails.img_url"
                label="Select Image (Make it simple for test purpose)"
                placeholder="Select one"
                :rules="[requiredValidator]"
                :items="images"
              />
            </VCol>

            <!-- 👉 Product Price -->
            <VCol cols="6">
              <AppTextField
                v-model="productDetails.price"
                label="Product Price"
                :rules="[requiredValidator]"
                placeholder="Enter price"
              />
            </VCol>

            <!-- 👉 Discount Price -->
            <VCol cols="6">
              <AppTextField
                v-model="productDetails.discount_price"
                label="Discount Price"
                :rules="[requiredValidator]"
                placeholder="Enter discount price"
              />
            </VCol>

            <!-- 👉 Submit and Cancel button -->
            <VCol
              cols="12"
              class="text-center"
            >
              <VBtn
                type="submit"
                class="me-3"
              >
                submit
              </VBtn>

              <VBtn
                variant="tonal"
                color="secondary"
                @click="resetForm"
              >
                Cancel
              </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>
