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

const props = defineProps({
  billingAddress: {
    type: Object,
    required: false,
    default: () => ({
      firstName: '',
      lastName: '',
      selectedCountry: null,
      addressLine1: '',
      addressLine2: '',
      landmark: '',
      contact: '',
      country: null,
      city: '',
      state: '',
      zipCode: null,
      is_stock: false,
    }),
  },
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
})

const emit = defineEmits([
  'update:isDialogVisible',
  'submit',
])

const billingAddress = ref(structuredClone(toRaw(props.billingAddress)))

const resetForm = () => {
  emit('update:isDialogVisible', false)
  billingAddress.value = structuredClone(toRaw(props.billingAddress))
}

const capitalizedLabel = (label) => {
  console.log(label)

  const convertLabelText = label.toString()

  return convertLabelText.charAt(0).toUpperCase() + convertLabelText.slice(1)
}

const onFormSubmit = () => {
  emit('update:isDialogVisible', false)
  emit('submit', billingAddress.value)
}


const images = [
{
    value: watch,
    title: 'Apple Watch 1',
  },
  {
    value: keyboard,
    title: 'Keyboard',
  },
  {
    value: show,
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
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 600 "
    :model-value="props.isDialogVisible"
    @update:model-value="val => $emit('update:isDialogVisible', val)"
  >
    <!-- 👉 Dialog close btn -->
    <DialogCloseBtn @click="$emit('update:isDialogVisible', false)" />

    <VCard
      v-if="props.billingAddress"
      class="pa-sm-10 pa-2"
    >
      <VCardText>
        <!-- 👉 Title -->
        <h4 class="text-h4 text-center mb-2">
          Add New Product
        </h4>
        <p class="text-body-1 text-center mb-6">
          Add new address for express delivery
        </p>

        <!-- 👉 Form -->
        <VForm @submit.prevent="onFormSubmit">
          <VRow>
            <!-- 👉 First Name -->
            <VCol
              cols="12"
            >
              <AppTextField
                v-model="billingAddress.name"
                label="Product Name"
                placeholder="Apple airpoed"
              />
            </VCol>

            <!-- 👉 Last Name -->
            <VCol
              cols="12"
            >
              <AppTextField
                v-model="billingAddress.seller"
                label="Seller name"
                placeholder="Apple"
              />
            </VCol>

            <!-- 👉 Last Name -->
            <VCol
              cols="12"
              md="6"
            >
              Available In Stock
              <VCheckbox
                v-model="billingAddress.is_stock"
                :label="capitalizedLabel(billingAddress.is_stock)"
              />
            </VCol>

            <!-- 👉 Quantity -->
            <VCol
              cols="6"
            >
              <AppTextField
                type="number"
                v-model="billingAddress.quantity"
                label="Quantity"
                placeholder="Enter quantity"
                searchable
              />
            </VCol>

            <!-- 👉 Select image -->
            <VCol
              cols="12"
              md="12">
              <AppSelect
                v-model="billingAddress.img_url"
                label="Select Image (Make it simple for test purpose)"
                placeholder="Select one"
                :rules="[requiredValidator]"
                :items="images"
              />
            </VCol>

            <!-- 👉 Product Price -->
            <VCol cols="6">
              <AppTextField
                v-model="billingAddress.price"
                label="Product Price"
                placeholder="Enter price"
              />
            </VCol>

            <!-- 👉 Discount Price -->
            <VCol cols="6">
              <AppTextField
                v-model="billingAddress.discount_price"
                label="Discount Price"
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
