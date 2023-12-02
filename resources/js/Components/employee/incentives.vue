<script setup>
import { initFlowbite } from "flowbite";
import { onMounted, ref } from "vue";
import { useForm, Head, Link } from "@inertiajs/vue3";

import Coffee from "@/Components/icons/gifts/Coffee.vue";
import Cupcake from "@/Components/icons/gifts/Cupcake.vue";
import Flower from "@/Components/icons/gifts/Flower.vue";
import Pizza from "@/Components/icons/gifts/Pizza.vue";
import QuillSliverPen from "@/Components/icons/gifts/QuillSliverPen.vue";

import axios from "axios";

import Loader from "./componentLoader.vue";

const props = defineProps({
	employee_id: String | Number,
});

const isLoadingIntialData = ref(true);

const incentiveArr = ref([]);
const incentiveLinks = ref(null);

const getData = async (url) => {
	if (!url || !props.employee_id) {
		return;
	}
	let res = await axios.post(url, {
		employee_id: props.employee_id,
	});

	incentiveArr.value = res.data?.data ?? [];
	incentiveLinks.value = res.data?.links ?? {
		next: false,
		prev: false,
	};
};

onMounted(async () => {
	await getData(route("incentivesOnly"));
	isLoadingIntialData.value = false;
});

const prevPage = async () => {
	const prevPageLink = incentiveLinks.value?.prev ?? "";

	if (prevPageLink) {
		await getData(prevPageLink);
	}
};

const nextPage = async () => {
	const nextPageLink = incentiveLinks.value?.next ?? "";

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
		<template v-if="incentiveArr.length > 0">
			<div>
				<ul>
					<li
						v-for="incentive in incentiveArr"
						:key="incentive.incentives_gift_incentives_gift_type_id"
						class="py-2 sm:py-3 mb-3 rounded-lg border px-3"
					>
						<Link
							:href="
								route(
									'incentive_gift.show',
									incentive.incentives_gift_incentives_gift_type_id
								)
							"
							class="hover:opacity-70"
						>
							<div class="flex items-center space-x-4">
								<div
									class="shadow-md w-10 h-10 rounded-full flex p-2 items-center justify-center"
								>
									<Coffee
										v-if="incentive?.incentives_icon_name == 'coffee'"
										w_class="w-6"
										h_class="h-6"
									/>
									<Cupcake
										v-if="incentive?.incentives_icon_name == 'cupcake'"
										w_class="w-6"
										h_class="h-6"
									/>
									<Flower
										v-if="incentive?.incentives_icon_name == 'flower'"
										w_class="w-6"
										h_class="h-6"
									/>
									<Pizza
										v-if="incentive?.incentives_icon_name == 'pizza'"
										w_class="w-6"
										h_class="h-6"
									/>
									<QuillSliverPen
										v-if="incentive?.incentives_icon_name == 'silver_pen'"
										w_class="w-6"
										h_class="h-6"
									/>
								</div>
								<div class="flex-1 min-w-0">
									<p
										class="text-sm font-medium text-gray-900 truncate dark:text-white"
									>
										{{ incentive?.incentives_name ?? "" }}
									</p>
									<p class="text-xs text-gray-500 truncate dark:text-gray-400">
										From: {{ incentive?.user_from_fullname ?? "" }}
									</p>
								</div>
								<div
									class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white"
								>
									${{
										incentive?.incentives_gift_transfer_amount_per_employee ??
										""
									}}
								</div>
							</div>
						</Link>
					</li>
				</ul>
				<div class="flex flex-wrap justify-between pt-6">
					<div class="w-full lg:w-auto mb-4 lg:mb-0 flex items-center"></div>
					<div class="w-full lg:w-auto flex items-center justify-center">
						<button
							v-if="incentiveLinks?.prev ?? false"
							@click="prevPage"
							class="inline-flex mr-3 items-center justify-center px-3 py-2 text-sm text-gray-500 border border-gray-300 bg-white hover:bg-indigo-50 rounded-2xl"
						>
							Previous
						</button>

						<button
							v-if="incentiveLinks?.next ?? false"
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
