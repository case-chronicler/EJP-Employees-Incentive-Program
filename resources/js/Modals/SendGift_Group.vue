<script setup>
import { initFlowbite } from "flowbite";
import { onMounted, ref, computed, watch, reactive, onBeforeMount } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import Swal from "sweetalert2";

import Coin from "@/Components/icons/coin.vue";

import ModalWrapper from "@/Modals/parts/ModalWrapper.vue";
import SendGiftUserSelect from "@/Modals/parts/SendGiftUserSelect.vue";

import { useModalStore } from "@/Store/modal";

import Pizza from "@/Components/icons/gifts/Pizza.vue";

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

	allUsers: {
		type: Array,
		required: true,
	},
});

const giftsAndPrices_new = reactive({
	individual: [],
	group: [],
});

const groupGiftsAndPrices = computed(() => {
	return giftsAndPrices_new?.group[0] ?? false;
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
const gift_price_per_employee = ref(0.0);
const gift_total_price = ref(0.0);
const gift_note = ref("");
const gift_id = ref("");
const group_gift_price = ref([50.0, 100.0, 150.0, 200.0, 250.0]);

const selected_users = ref([]);

const gift_employee = computed(() => {
	return selected_users.value;
});

const isFormDirty = computed(() => {
	if (
		selected_users.value.length == 0 ||
		!gift_name.value ||
		!gift_total_price.value ||
		!gift_note.value
	) {
		return true;
	}

	return false;
});

const form = useForm({
	gift_type: "",
	employees: [],
	gift_id: "",
	gift_total_price: "",
	gift_note: "",
	gift_price_per_employee: "",
});

const newGift_submit = () => {
	if (isFormDirty.value) {
		return;
	}

	const employees = gift_employee.value.map((elem) => {
		return elem?.employee_id ?? false;
	});

	form.gift_type = "group";
	form.employees = employees;
	form.gift_id = gift_id.value;
	form.gift_total_price = gift_total_price.value.toFixed(2);
	form.gift_note = gift_note.value;
	form.gift_price_per_employee = gift_price_per_employee.value;

	console.log(form);

	form.post(route("Incentive_gift.store"), {
		onFinish: () => form.reset(),
		onSuccess: () => {
			selected_users.value = [];
			modalStore.closeModal();

			Swal.fire({
				title: "hurray!",
				text: "Your gift has been sent",
				icon: "success",
			});
		},
	});
};

const processSelectedEmployees = (selectedEmployees) => {
	selected_users.value = selectedEmployees;
};

watch(selected_users, (newVal) => {
	if (newVal.length > 0) {
		gift_name.value = groupGiftsAndPrices.value?.name ?? "";
		gift_id.value = groupGiftsAndPrices.value?.gift_id ?? "";
	} else {
		gift_name.value = "";
		gift_id.value = "";
	}

	gift_price_per_employee.value = 0.0;
	gift_total_price.value = 0.0;
	gift_note.value = "";
});

watch(gift_total_price, (newVal) => {
	if (gift_employee.value.length == 0 || newVal == 0) {
		return;
	}

	gift_price_per_employee.value = newVal / gift_employee.value.length;

	gift_price_per_employee.value = gift_price_per_employee.value.toFixed(2);
});

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
						{{ form.errors.gift_note }}
						{{ form.errors.general }}
					</p>

					<div class="py-10">
						<div class="mb-3">
							<h6 class="text-sm mb-2 font-bold text-gray-700">Receiver(s)</h6>
							<transition
								leave-active-class="transition duration-100 ease-in"
								leave-from-class="opacity-100"
								leave-to-class="opacity-0"
							>
								<div
									v-if="selected_users.length > 0"
									class="flex items-center space-x-2 w-full mb-4"
								>
									<div v-for="employee in selected_users">
										<div class="relative p-2 bg-white rounded-lg shadow">
											<p>
												<span class="text-xs font-semibold text-gray-900">
													{{ employee.user_email }}
												</span>
												<br />
												<span class="text-gray-600 text-xs">
													{{
														employee.positions
															? employee.positions.join(", ")
															: ""
													}}
												</span>
											</p>
										</div>
									</div>
								</div>
							</transition>

							<div>
								<SendGiftUserSelect
									:allUsers="allUsers"
									@selectedEmployeesChanged="processSelectedEmployees"
								/>
							</div>
						</div>

						<template v-if="selected_users.length > 0">
							<div class="mt-10">
								<h6 class="text-sm mb-2 font-bold text-gray-700">
									Pick a group gift
								</h6>
								<div
									class="mb-8 w-full flex align-center justify-center max-w-sm"
								>
									<div>
										<input
											type="radio"
											:name="groupGiftsAndPrices.name"
											:value="groupGiftsAndPrices.name"
											:id="
												groupGiftsAndPrices.name + groupGiftsAndPrices.gift_id
											"
											v-model="gift_name"
											class="hidden"
										/>
										<label
											:for="
												groupGiftsAndPrices.name + groupGiftsAndPrices.gift_id
											"
										>
											<div
												v-if="groupGiftsAndPrices.name === 'pizza'"
												:class="{
													'bg-gray-300 ':
														gift_name === groupGiftsAndPrices.name,
												}"
												class="hover:cursor-pointer hover:bg-gray-200 shadow-md focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg px-3 py-1.5 mr-2 text-center inline-flex items-center"
											>
												<Pizza w_class="w-[70px]" h_class="h-[auto]" />
											</div>
										</label>
									</div>
								</div>

								<div v-if="gift_name !== ''">
									<div class="mb-2" v-if="gift_total_price == 0">
										<h6 class="text-sm mb-2 font-bold text-gray-700">
											Select amount
										</h6>
									</div>
									<div class="mb-2 text-xs" v-else>
										<div
											class="font-semibold text-gray-400 float-right text-right"
										>
											<div class="inline-flex items-center">
												total amount:
												<Coin
													class="mx-1"
													w_class="w-[20px]"
													h_class="h-[auto]"
												/>{{ gift_total_price }}
											</div>
											<br />
											<div class="inline-flex items-center">
												each employee receives
												<span class="mx-1 inline-flex items-center">
													<Coin
														class="mr-1"
														w_class="w-[20px]"
														h_class="h-[auto]"
													/>
													{{ gift_price_per_employee }}
												</span>
												worth of pizza slices
											</div>
										</div>
										<div class="clear-both"></div>
									</div>
									<div>
										<div class="inline-flex" role="group">
											<div v-for="price in group_gift_price" :key="price">
												<input
													type="radio"
													:name="groupGiftsAndPrices.name"
													:value="price"
													:id="'groupGiftsAndPrices' + price"
													v-model="gift_total_price"
													class="hidden"
												/>
												<label :for="'groupGiftsAndPrices' + price">
													<span
														:class="{
															'bg-gray-300 ': gift_total_price === price,
															'bg-white ': gift_total_price !== price,
														}"
														class="text-gray-900 hover:bg-gray-300 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-2 text-center inline-flex items-center me-2 mb-2"
													>
														<Coin
															class="mr-1"
															w_class="w-[20px]"
															h_class="h-[auto]"
														/>
														{{ price }}
													</span>
												</label>
											</div>
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
						</template>

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
