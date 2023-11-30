<script setup>
import { initFlowbite } from "flowbite";
import { onMounted, ref, computed, watch, reactive, onBeforeMount } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import Swal from "sweetalert2";

import ModalWrapper from "@/Modals/parts/ModalWrapper.vue";
import SendGiftUserSelect from "@/Modals/parts/SendGiftUserSelect.vue";

import { useModalStore } from "@/Store/modal";

import Coin from "@/Components/icons/coin.vue";
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
	allGifts: {
		type: Array,
		required: true,
	},
	selected_users: Object,
});

const giftsAndPrices_new = reactive({
	individual: [],
	group: [],
});

onBeforeMount(() => {
	if (props.allGifts.length == 0) {
		return;
	}

	props.allGifts.forEach((elem) => {
		const gift_details = {
			name: elem.icon_name,
			gift_name: elem.name,
			gift_id: elem.incentive_id,
			unit_price: elem.amount_per_item,
		};

		switch (elem.type) {
			case "individual":
				giftsAndPrices_new.individual.push(gift_details);
				break;
			case "group":
				giftsAndPrices_new.group.push(gift_details);
				break;
		}
	});
});

const gift_name = ref("");
const gift_unit_price = ref(0.0);
const gift_quantity = ref(0);
const gift_total_price = ref(0.0);
const gift_note = ref("");
const gift_id = ref("");

const gift_employee = computed(() => {
	return props.selected_users;
});

const isFormDirty = computed(() => {
	const employee_id = gift_employee.value?.user_id ?? false;

	if (
		!employee_id ||
		!gift_name.value ||
		!gift_total_price.value ||
		gift_quantity.value < 1 ||
		!gift_note.value
	) {
		return true;
	}

	return false;
});

watch(gift_quantity, (newVal, oldVal) => {
	gift_total_price.value = gift_unit_price.value * gift_quantity.value;
});

watch(gift_name, (newVal, oldVal) => {
	gift_unit_price.value = 0.0;
	gift_quantity.value = 0;
	gift_total_price.value = 0.0;
	gift_id.value = "";

	let gifts = giftsAndPrices_new.individual;

	if (!gifts) {
		return;
	}

	let gift = gifts.find((elem) => {
		return elem.name == newVal;
	});

	if (!gift) {
		return;
	}

	gift_id.value = gift.gift_id;
	gift_unit_price.value = gift.unit_price;
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

const form = useForm({
	gift_type: "",
	employees: [],
	gift_id: "",
	gift_total_price: "",
	gift_quantity: "",
	gift_note: "",
});

const newGift_submit = () => {
	if (isFormDirty.value) {
		return;
	}

	const employee_id = gift_employee.value?.employee_id ?? false;

	form.gift_type = "individual";
	form.employees = [employee_id];
	form.gift_id = gift_id.value;
	form.gift_total_price = gift_total_price.value.toFixed(2);
	form.gift_note = gift_note.value;
	form.gift_quantity = gift_quantity.value.toFixed(2);

	form.post(route("Incentive_gift.store"), {
		onFinish: () => form.reset(),
		onSuccess: () => {
			modalStore.closeModal();

			Swal.fire({
				title: "hurray!",
				text: "Your gift has been sent",
				icon: "success",
			});
		},
	});
};

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
					<p class="text-red-800 text-sm">
						{{ form.errors.gift_type }}
						{{ form.errors.employees }}
						{{ form.errors.gift_total_price }}
						{{ form.errors.gift_quantity }}
						{{ form.errors.gift_note }}
						{{ form.errors.general }}
					</p>

					<div class="py-10">
						<div>
							<h6 class="text-sm mb-2 font-bold text-gray-700">Receiver</h6>
							<div class="flex items-center justify-between space-x-5">
								<div class="flex items-center flex-1 min-w-0">
									<div class="flex-1 min-w-0">
										<p class="text-lg font-bold text-gray-800 truncate">
											{{ gift_employee.user_email }}
										</p>
										<p class="text-gray-600 text-sm">
											{{
												gift_employee.positions
													? gift_employee.positions.join(", ")
													: ""
											}}
										</p>
									</div>
								</div>
							</div>
						</div>

						<div class="mt-10">
							<h6 class="mb-4 text-sm font-bold text-gray-700">Pick a gift</h6>
							<div class="mb-8 grid grid-cols-4 gap-3 max-w-sm">
								<div
									v-for="(gift, index) in giftsAndPrices_new.individual"
									:key="index"
									class="w-16 h-16 hover:cursor-pointer"
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
										<div class="flex flex-col">
											<div
												v-if="gift.name === 'coffee'"
												:class="{ 'bg-gray-300 ': gift_name === gift.name }"
												class="w-full hover:cursor-pointer hover:bg-gray-200 shadow-md focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg px-3 py-1.5 text-center inline-flex items-center justify-center"
											>
												<Coffee />
											</div>
											<div
												v-if="gift.name === 'cupcake'"
												:class="{ 'bg-gray-300 ': gift_name === gift.name }"
												class="w-full hover:cursor-pointer hover:bg-gray-200 shadow-md focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg px-3 py-1.5 text-center inline-flex items-center justify-center"
											>
												<Cupcake />
											</div>
											<div
												v-if="gift.name === 'flower'"
												:class="{ 'bg-gray-300 ': gift_name === gift.name }"
												class="w-full hover:cursor-pointer hover:bg-gray-200 shadow-md focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg px-3 py-1.5 text-center inline-flex items-center justify-center"
											>
												<Flower />
											</div>
											<div
												v-if="gift.name === 'silver_pen'"
												:class="{ 'bg-gray-300 ': gift_name === gift.name }"
												class="w-full hover:cursor-pointer hover:bg-gray-200 shadow-md focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg px-3 py-1.5 text-center inline-flex items-center justify-center"
											>
												<QuillSliverPen />
											</div>
											<div
												class="text-xs font-semibold text-gray-500 text-center"
											>
												<span class="inline-flex items-center mt-2">
													<Coin
														class=""
														w_class="w-[10px]"
														h_class="h-[auto]"
													/>
												</span>
												{{ gift.unit_price }}
											</div>
										</div>

										<!-- Pizza -->
										<div
											v-if="gift.name === 'pizza'"
											:class="{ 'bg-gray-300 ': gift_name === gift.name }"
											class="w-full hover:cursor-pointer hover:bg-gray-200 shadow-md focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg px-3 py-1.5 text-center inline-flex items-center justify-center"
										>
											<Pizza />
										</div>
									</label>
								</div>
							</div>
							<div v-if="gift_name !== ''">
								<div class="mb-2">
									<div class="font-normal text-gray-700 dark:text-gray-400">
										<div
											v-if="gift_name === 'coffee'"
											class="inline-block inline-flex items-center mr-2"
										>
											<Coffee w_class="w-[16px]" h_class="h-[16px]" />
										</div>
										<div
											v-if="gift_name === 'cupcake'"
											class="inline-block nline-flex items-center mr-2"
										>
											<Cupcake w_class="w-[16px]" h_class="h-[16px]" />
										</div>
										<div
											v-if="gift_name === 'flower'"
											class="inline-block inline-flex items-center mr-2"
										>
											<Flower w_class="w-[16px]" h_class="h-[16px]" />
										</div>
										<div
											v-if="gift_name === 'pizza'"
											class="inline-bloc inline-flex items-center mr-2"
										>
											<Pizza w_class="w-[16px]" h_class="h-[16px]" />
										</div>
										<div
											v-if="gift_name === 'silver'"
											class="inline-block font-me lex items-center mr-2"
										>
											<QuillSliverPen w_class="w-[16px]" h_class="h-[16px]" />
										</div>

										x {{ gift_quantity }} = ${{ gift_total_price }}
									</div>
								</div>
								<div>
									<div class="inline-flex rounded-md shadow-sm" role="group">
										<button
											@click="decreaseQuantity"
											type="button"
											class="px-3 py-1.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700"
										>
											<Minus />
										</button>
										<button
											@click="increaseQuantity"
											type="button"
											class="px-3 py-1.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-r-md hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700"
										>
											<Add />
										</button>
									</div>
								</div>

								<div class="my-6">
									<label for="message" class="block mb-2">
										<h6 class="mb-3 text-sm font-bold text-gray-700">
											Add a note
										</h6>
									</label>
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

						<div class="py-3 sm:flex sm:flex-row-reverse">
							<button
								@click.prevent="newGift_submit"
								:disabled="form.processing || isFormDirty"
								type="button"
								:class="{
									'bg-gray-400 hover:cursor-not-allowed': isFormDirty,
									'bg-gray-800 hover:bg-gray-500': !isFormDirty,
								}"
								class="inline-flex w-full justify-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto"
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
