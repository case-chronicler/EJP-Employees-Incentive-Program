<script setup>
import { initFlowbite } from "flowbite";
import { onMounted, ref } from "vue";
import { useForm, Head, Link, router } from "@inertiajs/vue3";
import axios from "axios";

const props = defineProps({
	employee_id: String | Number,
});

import Loader from "./componentLoader.vue";

const isLoadingIntialData = ref(true);
const transactionsArr = ref([]);
const transactionsLinks = ref(null);

const getData = async (url) => {
	if (!url || !props.employee_id) {
		return;
	}
	let res = await axios.post(url, {
		employee_id: props.employee_id,
	});

	transactionsArr.value = res.data?.data ?? [];
	transactionsLinks.value = res.data?.links ?? {
		next: false,
		prev: false,
	};
};

onMounted(async () => {
	await getData(route("transactionsOnly"));
	isLoadingIntialData.value = false;
});

const prevPage = async () => {
	const prevPageLink = transactionsLinks.value?.prev ?? "";

	if (prevPageLink) {
		await getData(prevPageLink);
	}
};

const nextPage = async () => {
	const nextPageLink = transactionsLinks.value?.next ?? "";

	if (nextPageLink) {
		await getData(nextPageLink);
	}
};
</script>

<template>
	<template v-if="isLoadingIntialData">
		<Loader />
	</template>
	<template v-else>
		<template v-if="transactionsArr.length > 0">
			<div>
				<ul>
					<li
						v-for="transaction in transactionsArr"
						:key="transaction.withdrawal_request_link_id"
						class="py-2 sm:py-3 mb-3 rounded-lg border px-3"
					>
						<Link
							:href="
								route(
									'withdrawal_requests.show',
									transaction.withdrawal_request_link_id
								)
							"
							class="hover:opacity-70"
						>
							<div class="flex items-center space-x-4">
								<div class="flex-1 min-w-0">
									<p
										class="text-sm font-medium text-gray-900 truncate dark:text-white"
									>
										{{
											transaction?.user_fullname ??
											transaction?.user_email ??
											""
										}}
									</p>
									<p class="text-sm text-gray-500 truncate dark:text-gray-400">
										status: {{ transaction?.withdrawal_request_status ?? "" }}
									</p>
								</div>
								<div
									class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white"
								>
									${{ transaction?.withdrawal_request_amount ?? "" }}
								</div>
							</div>
						</Link>
					</li>
				</ul>
				<div class="flex flex-wrap justify-between pt-6">
					<div class="w-full lg:w-auto mb-4 lg:mb-0 flex items-center"></div>
					<div class="w-full lg:w-auto flex items-center justify-center">
						<button
							v-if="transactionsLinks?.prev ?? false"
							@click="prevPage"
							class="inline-flex mr-3 items-center justify-center px-3 py-2 text-sm text-gray-500 border border-gray-300 bg-white hover:bg-indigo-50 rounded-2xl"
						>
							Previous
						</button>

						<button
							v-if="transactionsLinks?.next ?? false"
							@click="nextPage"
							class="inline-flex items-center justify-center px-3 py-2 text-sm text-gray-500 border border-gray-300 bg-white hover:bg-indigo-50 rounded-2xl"
						>
							Next
						</button>
					</div>
				</div>
			</div>
		</template>
		<template v-else>
			<div class="min-h-[20vh] flex items-center justify-center">
				<div>No data found</div>
			</div>
		</template>
	</template>
</template>
