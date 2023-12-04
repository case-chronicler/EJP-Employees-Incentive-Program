<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

import Coffee from "@/Components/icons/gifts/Coffee.vue";
import Cupcake from "@/Components/icons/gifts/Cupcake.vue";
import Flower from "@/Components/icons/gifts/Flower.vue";
import Pizza from "@/Components/icons/gifts/Pizza.vue";
import QuillSliverPen from "@/Components/icons/gifts/QuillSliverPen.vue";

import Coin from "@/Components/icons/coin.vue";
import User from "@/Components/icons/user_2.vue";

const props = defineProps({
	incentiveData_processed: Object,
});

const formatDate = (date) => {
	try {
		const options = { day: "numeric", month: "long", year: "numeric" };

		const today = new Date(date);
		const formattedDate = new Intl.DateTimeFormat("en-US", options).format(
			today
		);

		return formattedDate;
	} catch (error) {
		return "";
	}
};
</script>

<template>
	<Head title="Gift" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">Gift</h2>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="py-8">
					<div class="container px-4 mx-auto">
						<div class="p-6 mb-8 bg-white rounded-xl">
							<div class="relative h-24">
								<div
									class="absolute bottom-0 left-0 w-full flex flex-wrap p-6 items-center justify-between"
								>
									<div class="flex items-center w-full md:w-auto mb-5 md:mb-0">
										<div
											class="shadow-md font-medium rounded-full px-3 py-1.5 mr-2 text-center inline-flex justify-center items-center w-16 h-16 mr-2 md:mr-6"
										>
											<Coffee
												v-if="
													incentiveData_processed.incentives_icon_name ===
													'coffee'
												"
											/>
											<Cupcake
												v-if="
													incentiveData_processed.incentives_icon_name ===
													'cupcake'
												"
											/>
											<Flower
												v-if="
													incentiveData_processed.incentives_icon_name ===
													'flower'
												"
											/>
											<Pizza
												v-if="
													incentiveData_processed.incentives_icon_name ===
													'pizza'
												"
											/>
											<QuillSliverPen
												v-if="
													incentiveData_processed.incentives_icon_name ===
													'silver_pen'
												"
											/>
										</div>
										<div>
											<h5 class="text-xl text-gray-700 font-bold mb-2">
												{{ incentiveData_processed.incentives_name }}
												<span
													class="inline-block py-1 px-2 ml-2 text-gray-500 text-sm leading-6 bg-gray-200 rounded uppercase"
												>
													{{
														incentiveData_processed.incentives_gift_incentives_gift_type
													}}</span
												>
											</h5>
											<span class="text-gray-500 inline-flex items-center">
												<Coin class="mr-1" w_class="w-[auto]" h_class="h-5" />
												{{
													incentiveData_processed.incentives_gift_total_amount
												}}

												<span
													v-if="
														incentiveData_processed.incentives_gift_incentives_gift_type ==
														'group'
													"
												>
													<span class="mx-2">|</span>
													each recipient got
													{{
														incentiveData_processed.incentives_gift_transfer_amount_per_employee
													}}
												</span>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="flex flex-wrap -mx-4">
							<div class="mb-8 w-full lg:w-2/3 px-4">
								<div class="p-8 bg-white rounded-xl">
									<div class="pb-6 mb-8 border-b border-gray-400">
										<h3 class="text-lg font-semibold text-gray-700 mb-6">
											Note
										</h3>
										<p class="leading-normal text-gray-500 font-medium mb-4">
											{{ incentiveData_processed.incentives_gift_note ?? "" }}
										</p>
									</div>

									<div>
										<h3 class="text-lg font-semibold text-gray-700 mb-6">
											Recipient
										</h3>
										<div class="-mb-3">
											<span
												class="inline-block py-2 px-4 mb-3 mr-3 text-gray-500 leading-6 bg-gray-200 rounded-full"
												:key="index"
												v-for="(
													person, index
												) in incentiveData_processed.recipient"
												>{{ person }}</span
											>
										</div>
									</div>
								</div>
							</div>

							<div class="mb-8 w-full lg:w-1/3 px-4 mb-6 lg:mb-0">
								<div class="p-3 mb-6 bg-white rounded-xl">
									<ul>
										<li>
											<div
												class="block py-2 px-3 text-sm leading-6 text-gray-500 font-medium rounded-lg transition duration-100 flex justify-between"
											>
												<span> Type </span>
												<span class="uppercase">
													{{
														incentiveData_processed.incentives_gift_incentives_gift_type ??
														""
													}}
												</span>
											</div>
										</li>
										<li>
											<div
												class="block py-2 px-3 text-sm leading-6 text-gray-500 font-medium rounded-lg transition duration-100 flex justify-between"
											>
												<span> Date </span>
												<span class="uppercase">
													{{
														formatDate(
															incentiveData_processed.incentives_gift_created_at
														) ?? ""
													}}
												</span>
											</div>
										</li>
										<li>
											<div
												class="block py-2 px-3 text-sm leading-6 text-gray-500 font-medium rounded-lg transition duration-100 flex justify-between"
											>
												<span> Quantity </span>
												<span class="uppercase">
													{{
														incentiveData_processed.incentives_gift_gift_quantity ??
														""
													}}
												</span>
											</div>
										</li>
										<li>
											<div
												class="block py-2 px-3 text-sm leading-6 text-gray-500 font-medium rounded-lg transition duration-100 flex justify-between"
											>
												<span> Sender(s) </span>
												<span class="text-right">
													<span
														class="text-xs inline-block py-1 px-3 ml-1 mb-1 text-gray-500 leading-6 bg-gray-200 rounded-full"
														v-for="(
															sender, index
														) in incentiveData_processed.sender"
													>
														{{ sender ?? "" }}
													</span>
												</span>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
