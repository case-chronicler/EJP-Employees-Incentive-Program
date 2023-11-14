<script setup>
import axios from "axios";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import EmployeeSvgIcon from "@/Components/icons/employee_icon.vue";
import SuitcaseIcon from "@/Components/icons/suitcase_icon.vue";
import GiftIcon from "@/Components/icons/gift.vue";
import NewEmployeeInvite from "@/Modals/NewEmployeeInvite.vue";
import SendGift from "@/Modals/SendGift.vue";
import SendGift_Group from "@/Modals/SendGift_Group.vue";

import Dropdown from "@/Components/Dropdown.vue";

import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { computed, reactive, ref } from "vue";

import { useModalStore } from "@/Store/modal";

const modalStore = useModalStore();

const props = defineProps({
	allPositions: Array,
	allGifts: Array,
	users_and_employees: Object,
	isEmployeeAnAttorney: Boolean,
});

const gift_type = ref("");
const selected_users = ref([]);
const allUsers = ref([]);

const requestsResult = reactive({
	addRole: null,
});

const processed_users_and_employees = computed(() => {
	const processed_users_and_employees = props.users_and_employees;
	if (requestsResult.addRole === null) {
		return processed_users_and_employees;
	} else {
		for (const key in requestsResult.addRole) {
			if (Object.hasOwnProperty.call(requestsResult.addRole, key)) {
				const element = requestsResult.addRole[key];

				processed_users_and_employees[key] = element;
			}
		}

		return processed_users_and_employees;
	}
});

const addRole = async (user_id, position_id) => {
	// console.log(user_id);
	// return;
	try {
		let res = await axios.post(route("employee.store"), {
			user_id: user_id,
			position_id: position_id,
		});

		const processed_users_and_employees =
			res?.data?.processed_users_and_employees;
		requestsResult.addRole = processed_users_and_employees;
	} catch (error) {}
};

const initSendGit = (selected_gift_type, selected_user = []) => {
	if (selected_gift_type == "" && selected_user == []) {
		return;
	}
	gift_type.value = selected_gift_type;
	selected_users.value = selected_user;

	modalStore.openSendGiftModal();
};

const initSendGit_Group = (selected_gift_type) => {
	if (selected_gift_type == "") {
		return;
	}
	gift_type.value = selected_gift_type;
	allUsers.value = [];

	for (const key in processed_users_and_employees.value) {
		if (Object.hasOwnProperty.call(processed_users_and_employees.value, key)) {
			const element = processed_users_and_employees.value[key];

			if (element.user_id === usePage().props.auth.user.user_id) {
				continue;
			}

			allUsers.value.push(element);
		}
	}

	modalStore.openSendGift_GroupModal();
};
</script>

<template>
	<NewEmployeeInvite
		:allPositions="allPositions"
		v-if="modalStore.currentModal === 'NEW_EMPLOYEE_INVITE'"
	/>
	<SendGift
		:gift_type="gift_type"
		:allGifts="allGifts"
		:selected_users="selected_users"
		v-if="modalStore.currentModal === 'SEND_GIFT'"
	/>
	<SendGift_Group
		:gift_type="gift_type"
		:allGifts="allGifts"
		:allUsers="allUsers"
		v-if="modalStore.currentModal === 'SendGift_Group'"
	/>

	<Head title="Employees" />

	<!-- {{ users_and_employees }}
	{{ allPositions }} -->
	<!-- {{ isEmployeeAnAttorney }} -->

	<AuthenticatedLayout>
		<template #header>
			<div class="flex items-center space-x-4">
				<h2 class="font-semibold text-xl text-gray-800 leading-tight">
					Employees
				</h2>

				<PrimaryButton
					v-if="isEmployeeAnAttorney"
					type="button"
					class="float-right"
					@click.prevent="modalStore.openNewEmployeeInviteModal"
					>add new employee</PrimaryButton
				>
			</div>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-6">
				<div class="mb-4" v-if="isEmployeeAnAttorney">
					<PrimaryButton
						@click.prevent="initSendGit_Group('group', [])"
						type="button"
						class="mx-2 float-right"
						>group gift</PrimaryButton
					>
					<div class="clear-both"></div>
				</div>

				<div class="mt-6 rounded-lg">
					<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
						<template
							v-for="user_and_employee in processed_users_and_employees"
							:key="user_and_employee.user_id"
						>
							<!-- component -->
							<div
								v-if="
									$page.props.auth.user.user_id !== user_and_employee.user_id
								"
								class="bg-white shadow-md p-6 rounded-2xl border-2 border-gray-50"
							>
								<div class="flex flex-col">
									<div>
										<h2 class="font-bold text-gray-600 text-center">
											{{ user_and_employee.user_fullname }}
										</h2>
									</div>
									<div class="my-6">
										<div class="w-full flex flex-row space-x-4 items-center">
											<div>
												<div class="h-18 w-18 border-2 rounded-full p-3">
													<EmployeeSvgIcon />
												</div>
											</div>
											<div>
												<!-- component -->
												<!-- This is an example component -->

												<div
													class="relative w-full font-semibold flex flex-col min-h-[100px] items-center justify-center"
												>
													<div v-if="user_and_employee.positions.length == 0">
														<p class="ml-2 text-gray-700 text-xs">
															has no role
														</p>
													</div>
													<div v-else>
														<div
															v-for="positions in user_and_employee.positions"
															:key="positions"
															class="flex mb-2"
														>
															<div>
																<SuitcaseIcon />
															</div>
															<div class="ml-2 text-navy-700 text-xs">
																{{ positions }}
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div
										v-if="isEmployeeAnAttorney"
										class="w-full text-right border-t-2 border-gray-100 py-4 flex items-end justify-end"
									>
										<Dropdown>
											<template #trigger>
												<div>
													<span
														class="underline px-3 py-1.5 mr-2 text-sm font-semibold text-gray-600 transition-all duration-200 hover:text-gray-700 hover:cursor-pointer"
														>add role</span
													>
												</div>
											</template>
											<template #content>
												<button
													class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition duration-150 ease-in-out"
													@click.prevent="
														addRole(
															user_and_employee.user_id,
															current_position.position_id
														)
													"
													v-for="current_position in allPositions"
													:key="current_position.position_id"
												>
													{{ current_position.position_name }}
												</button>
											</template>
										</Dropdown>
										<button
											@click="initSendGit('individial', user_and_employee)"
											type="button"
											class="text-gray-900 hover:bg-gray-200 shadow focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg px-3 py-1.5 mr-2 text-center inline-flex items-center"
										>
											<GiftIcon />
										</button>
									</div>
								</div>
							</div>
						</template>
					</div>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
