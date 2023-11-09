<script setup>
import { initFlowbite } from "flowbite";
import { onMounted, ref, computed, watch } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import NoticeIcon from "@/Components/icons/notice_icon.vue";
import User from "@/Components/icons/user.vue";

import ModalWrapper from "@/Modals/parts/ModalWrapper.vue";
import SendGiftUserSelect from "@/Modals/parts/SendGiftUserSelect.vue";

import { useModalStore } from "@/Store/modal";

import Add from "@/Components/icons/gifts/Add.vue";
import Coffee from "@/Components/icons/gifts/Coffee.vue";
import Cupcake from "@/Components/icons/gifts/Cupcake.vue";
import Flower from "@/Components/icons/gifts/Flower.vue";
import Minus from "@/Components/icons/gifts/Minus.vue";
import Pizza from "@/Components/icons/gifts/Pizza.vue";
import QuillSliverPen from "@/Components/icons/gifts/QuillSliverPen.vue";

const user = usePage().props.auth.user;

const modalStore = useModalStore();

const props = defineProps({
	gift_type: {
		type: String,
		required: true,
	},
	user: Object,
});

const newInviteForm_submit = () => {
	if (form.processing) {
		return;
	}
	const newInviteForm = document.querySelector("#newInviteForm") ?? false;

	if (newInviteForm) {
		form.post(route("invite.store"), {
			onSuccess: () => form.reset(),
		});
	}
};

const giftsAndPrices = ref([
	{
		type: "individual",
		items: [
			{
				name: "coffee",
				unit_price: 5,
			},
			{
				name: "cupcake",
				unit_price: 2.5,
				type: "individual",
			},
			{
				name: "flower",
				unit_price: 25,
				type: "individual",
			},
			{
				name: "silver",
				unit_price: 10,
				type: "individual",
			},
		],
	},
]);

const gift_type = ref("individual");
const gift_name = ref("");
const gift_unit_price = ref(0.0);
const gift_quantity = ref(0);
const gift_total_price = ref(0.0);
const gift_note = ref("");

const gift_employee = computed(() => {
	return props.user
})

const isFormDirty = computed(() => {
	const employee_id = gift_employee.value?.user_id ?? false

	if(
		!employee_id || 
		!gift_name.value ||
		!gift_total_price.value ||
		gift_quantity.value < 1 ||
		!gift_note.value
	){		
		return true
	}

	return false
})

watch(gift_quantity, (newVal, oldVal) => {
	gift_total_price.value = gift_unit_price.value * gift_quantity.value;
});

watch(gift_name, (newVal, oldVal) => {
	gift_unit_price.value = 0.0;
	gift_quantity.value = 0;
	gift_total_price.value = 0.0;

	let gifts = giftsAndPrices.value.filter((elem) => {
		return elem.type === gift_type.value;
	});

	if (!gifts) {
		return;
	}

	gifts = gifts[0];

	gifts = gifts.items.find((elem) => {
		return elem.name == newVal;
	});

	if (!gifts) {
		return;
	}

	gift_unit_price.value = gifts.unit_price;
	gift_quantity.value = 1;

	gift_total_price.value = gift_unit_price.value * gift_quantity.value;
});

const increaseQuantity = () => {
	gift_quantity.value += 1;
};

const decreaseQuantity = () => {
	if (gift_quantity.value === 1) {
		return;
	}
	gift_quantity.value -= 1;
};

const getIndividualGiftsAndPrices = computed(() => {
	let individualGiftsAndPrices = giftsAndPrices.value.filter((elem) => {
		return elem.type === gift_type.value;
	});

	if (!individualGiftsAndPrices) {
		return [];
	}

	individualGiftsAndPrices = individualGiftsAndPrices[0];

	return individualGiftsAndPrices?.items ?? [];
});

const form = useForm({
	gift_type: "",
	users: [],
	gift_id: "",
	gift_total_price: "",
	gift_quantity: "",
	gift_note: "",
});

const newGift_submit = () => {
	if(isFormDirty.value){
		return
	}

	const employee_id = gift_employee.value?.user_id ?? false

	form.gift_type = 'individual'
	form.users = [employee_id]
	form.gift_id = '1'
	form.gift_total_price = gift_total_price.value.toFixed(2)
	form.gift_note = gift_note.value
	form.gift_quantity = gift_quantity.value.toFixed(2)

	form.post(route("Incentive_gift.store"), {
		onFinish: () => form.reset(),
	});
}

// initialize components based on data attribute selectors
onMounted(() => {
	initFlowbite();
});
</script>

<template>
	
	<ModalWrapper>

		<div
			class="flex min-h-full items-end justify-center p-4 sm:items-center sm:p-0"
		>
			<div
				class="pt-0 pr-4 pb-0 pl-4 mt-0 mr-auto mb-0 ml-auto w-[600px] max-w-4xl sm:px-6 lg:px-8"
			>
				<div
					class="bg-white shadow-xl mt-8 mr-0 mb-0 ml-0 pt-4 pr-10 pb-4 pl-10 flow-root rounded-lg sm:py-2"
				>
					<div class="py-10">
						{{ form.errors }}
						<div>
							<div
								class="flex items-center justify-between space-x-5"							
							>
							
								<div class="flex items-center flex-1 min-w-0">
									<div class="flex-1 min-w-0">
										<p class="text-lg font-bold text-gray-800 truncate">
											{{ gift_employee.user_email }}
										</p>
										<p class="text-gray-600 text-sm">{{ (gift_employee.positions) ? gift_employee.positions.join(', ') : '' }}</p>
									</div>
								</div>									
							</div>
						</div>

						<div class="mt-10">
							<p class="mb-4">Pick a gift</p>
							<div class="mb-8 grid grid-cols-4 gap-3 max-w-sm">
								<div
									v-for="(gift, index) in getIndividualGiftsAndPrices"
									:key="index"
									class="w-12 h-12 hover:cursor-pointer"
								>
									<input
										type="radio"
										:name="gift.name"
										:value="gift.name"
										:id="gift.name + index"
										v-model="gift_name"
										class="hidden"
									/>
									<label :for="gift.name + index">
										<div
											v-if="gift.name === 'coffee'"
											:class="{ 'bg-gray-300 ': gift_name === gift.name }"
											class="hover:cursor-pointer hover:bg-gray-200 shadow-md focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg px-3 py-1.5 mr-2 text-center inline-flex items-center"
										>
											<Coffee />
										</div>
										<div
											v-if="gift.name === 'cupcake'"
											:class="{ 'bg-gray-300 ': gift_name === gift.name }"
											class="hover:cursor-pointer hover:bg-gray-200 shadow-md focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg px-3 py-1.5 mr-2 text-center inline-flex items-center"
										>
											<Cupcake />
										</div>
										<div
											v-if="gift.name === 'flower'"
											:class="{ 'bg-gray-300 ': gift_name === gift.name }"
											class="hover:cursor-pointer hover:bg-gray-200 shadow-md focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg px-3 py-1.5 mr-2 text-center inline-flex items-center"
										>
											<Flower />
										</div>
										<div
											v-if="gift.name === 'pizza'"
											:class="{ 'bg-gray-300 ': gift_name === gift.name }"
											class="hover:cursor-pointer hover:bg-gray-200 shadow-md focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg px-3 py-1.5 mr-2 text-center inline-flex items-center"
										>
											<Pizza />
										</div>
										<div
											v-if="gift.name === 'silver'"
											:class="{ 'bg-gray-300 ': gift_name === gift.name }"
											class="hover:cursor-pointer hover:bg-gray-200 shadow-md focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg px-3 py-1.5 mr-2 text-center inline-flex items-center"
										>
											<QuillSliverPen />
										</div>
									</label>
								</div>
							</div>
							<div v-if="gift_name !== ''">
								<div class="mb-2">
									<p class="font-normal text-gray-700 dark:text-gray-400">
										<div
											v-if="gift_name === 'coffee'"											
											class="inline-block inline-flex items-center mr-2"
										>
											<Coffee w_class="w-[16px]" h_class="h-[16px]"/>
										</div>
										<div
											v-if="gift_name === 'cupcake'"											
											class="inline-block  nline-flex items-center mr-2"
										>
											<Cupcake w_class="w-[16px]" h_class="h-[16px]"/>
										</div>
										<div
											v-if="gift_name === 'flower'"											
											class="inline-block inline-flex items-center mr-2"
										>
											<Flower w_class="w-[16px]" h_class="h-[16px]"/>
										</div>
										<div
											v-if="gift_name === 'pizza'"											
											class="inline-bloc  inline-flex items-center mr-2"
										>
											<Pizza w_class="w-[16px]" h_class="h-[16px]"/>
										</div>
										<div
											v-if="gift_name === 'silver'"											
											class="inline-block font-me lex items-center mr-2"
										>
											<QuillSliverPen w_class="w-[16px]" h_class="h-[16px]"/>
										</div>

										 x {{ gift_quantity }} = ${{
											gift_total_price
										}}
									</p>
								</div>
								<div>
									<div class="inline-flex rounded-md shadow-sm" role="group">
										<button
											@click="decreaseQuantity"
											type="button"
											class="px-3 py-1.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700"
										>
											<Minus/>
										</button>
										<button
											@click="increaseQuantity"
											type="button"
											class="px-3 py-1.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-r-md hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700"
										>
											<Add/>
										</button>
									</div>
								</div>

								<div class="my-3">
									<label
										for="message"
										class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
										>Add note</label
									>
									<textarea
										id="message"
										v-model="gift_note"
										rows="4"
										class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
										placeholder="Write your note here..."
									></textarea>
								</div>
							</div>
						</div>

						<div class=" py-3 sm:flex sm:flex-row-reverse">
							<button
								@click.prevent="newGift_submit"
								:disabled="form.processing || isFormDirty"
								type="button"
								:class="{
									'bg-gray-400 hover:cursor-not-allowed': isFormDirty,
									'bg-gray-800 hover:bg-gray-500': !isFormDirty
								}"
								class="inline-flex w-full justify-center rounded-md  px-3 py-2 text-sm font-semibold text-white shadow-sm  sm:ml-3 sm:w-auto"
							>
								Send gift
							</button>
							<button
								@click.prevent="modalStore.closeModal"
								type="button"
								class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
							>
								Cancel
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</ModalWrapper>
</template>
