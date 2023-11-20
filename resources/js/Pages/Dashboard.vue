<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

import Coin from "@/Components/icons/coin.vue";
import User from "@/Components/icons/user.vue";
import Group from "@/Components/icons/group.vue";
import GiftIcon from "@/Components/icons/gift.vue";

const props = defineProps({
	incentive_gift: Array,
	withdrawal_request: Array,
	isLoggedInUserAnAttorney: Boolean,
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
	<Head title="Dashboard" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Dashboard
			</h2>
		</template>

		<div class="py-20">
			<div class="max-w-7xl w-full mx-auto sm:px-6 lg:px-8">
				<div class="block mb-20 lg:flex lg:flex-row gap-x-10">
					<div
						class="relative flex mx-4 mb-14 lg:mx-0 lg:mb-0 lg:basis-7/12 flex-col rounded-2xl bg-white"
					>
						<div
							class="flex h-fit w-full items-center justify-between rounded-t-2xl bg-white px-4 pb-[20px] pt-4 shadow-2xl shadow-gray-100 dark:!bg-navy-700 dark:shadow-none"
						>
							<h4 class="text-lg font-bold text-navy-700 dark:text-white">
								Recent Withdrawal Requests
							</h4>
							<Link
								v-if="withdrawal_request.length != 0"
								as="button"
								class="linear rounded-[20px] bg-lightPrimary px-4 py-2 text-base font-medium text-brand-500 transition duration-200 hover:bg-gray-100 active:bg-gray-200"
								:href="route('withdrawals.index')"
							>
								See all
							</Link>
						</div>
						<div
							v-if="withdrawal_request.length == 0"
							class="min-h-[30vh] flex items-center justify-center"
						>
							<div>No data found</div>
						</div>
						<div
							v-else
							class="w-full overflow-x-scroll px-4 md:overflow-x-hidden min-h-[30vh]"
						>
							<table
								role="table"
								class="w-full min-w-[500px] overflow-x-scroll"
							>
								<thead>
									<tr role="row">
										<th
											colspan="1"
											role="columnheader"
											title="Toggle SortBy"
											style="cursor: pointer"
										>
											<div
												class="flex items-center justify-between pb-2 pt-4 text-start uppercase tracking-wide text-gray-600 sm:text-xs lg:text-xs"
											>
												Email
											</div>
										</th>
										<th
											colspan="1"
											role="columnheader"
											title="Toggle SortBy"
											style="cursor: pointer"
										>
											<div
												class="flex items-center justify-between pb-2 pt-4 text-start uppercase tracking-wide text-gray-600 sm:text-xs lg:text-xs"
											>
												Date
											</div>
										</th>
										<th
											colspan="1"
											role="columnheader"
											title="Toggle SortBy"
											style="cursor: pointer"
										>
											<div
												class="flex items-center justify-between pb-2 pt-4 text-start uppercase tracking-wide text-gray-600 sm:text-xs lg:text-xs"
											>
												Amount
											</div>
										</th>
									</tr>
								</thead>
								<tbody role="rowgroup" class="px-4">
									<tr
										role="row"
										v-for="(wr, index) in withdrawal_request"
										:key="index"
									>
										<td class="py-3 text-sm" role="cell">
											<div class="flex items-center gap-2">
												<div
													class="h-8 w-8 rounded-full flex items-center justify-center shadow-lg"
												>
													<User w_class="w-5" h_class="h-5" />
												</div>
												<p
													class="text-sm font-medium text-navy-700 dark:text-white"
												>
													<Link
														:href="
															route(
																'withdrawal_requests.show',
																wr.withdrawal_requests_withdrawal_request_link_id
															)
														"
														class="flex cursor-pointer items-center transition hover:text-slate-600"
													>
														{{ wr.user_email }}
													</Link>
												</p>
											</div>
										</td>
										<td class="py-3 text-sm" role="cell">
											<p
												class="text-sm font-medium text-gray-600 dark:text-white"
											>
												{{ formatDate(wr.withdrawal_requests_created_at) }}
											</p>
										</td>
										<td class="py-3 text-sm" role="cell">
											<div class="mx-2 flex font-bold">
												<div
													class="text-sm text-neutral-500 inline-flex items-center"
												>
													<Coin class="mr-1" w_class="w-[auto]" h_class="h-4" />
													{{ wr.withdrawal_requests_amount }}
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div
						aria-label="card"
						class="p-8 rounded-2xl bg-white mx-4 mb-14 lg:mx-0 lg:mb-0 lg:basis-5/12"
					>
						<div aria-label="header" class="flex items-center space-x-2">
							<GiftIcon w_class="w-[auto]" h_class="h-8" />
							<div class="space-y-0.5 flex-1">
								<h3
									class="font-medium text-lg tracking-tight text-gray-900 leading-tight"
								>
									Incentives
								</h3>
								<p class="text-sm font-normal text-gray-400 leading-none">
									4 most recent gifts
								</p>
							</div>

							<Link
								v-if="incentive_gift.length != 0"
								class="inline-flex items-center shrink-0 justify-center w-8 h-8 rounded-full text-white bg-gray-900 focus:outline-none"
								:href="route('incentive_gift.index')"
							>
								<svg
									xmlns="http://www.w3.org/2000/svg"
									class="w-5 h-5"
									width="24"
									height="24"
									viewBox="0 0 24 24"
									stroke-width="1.5"
									stroke="currentColor"
									fill="none"
									stroke-linecap="round"
									stroke-linejoin="round"
								>
									<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
									<path d="M17 7l-10 10"></path>
									<path d="M8 7l9 0l0 9"></path>
								</svg>
							</Link>
						</div>
						<div
							v-if="incentive_gift.length == 0"
							class="min-h-[30vh] flex items-center justify-center"
						>
							<div>No data found</div>
						</div>
						<div v-else aria-label="content" class="mt-9 grid gap-2.5">
							<Link
								:href="
									route(
										'incentive_gift.show',
										iG.incentives_gift_incentives_gift_type_id
									)
								"
								class="hover:opacity-60"
								v-for="(iG, index) in incentive_gift"
								:key="index"
							>
								<div
									class="flex items-center space-x-4 p-3.5 rounded-full bg-gray-100"
								>
									<span
										class="flex items-center justify-center w-10 h-10 shrink-0 rounded-full bg-white text-gray-900"
									>
										<User
											v-if="
												iG.incentives_gift_incentives_gift_type == 'individual'
											"
											w_class="w-[auto]"
											h_class="h-6"
										/>
										<Group
											v-if="iG.incentives_gift_incentives_gift_type == 'group'"
											w_class="w-[auto]"
											h_class="h-8"
										/>
									</span>
									<div class="flex flex-col flex-1">
										<h3 class="text-sm font-medium">
											{{ iG.incentives_name }}
										</h3>
										<div class="flex divide-x divide-gray-200 mt-1">
											<div
												class="px-3 text-sm leading-none text-gray-400 font-normal first:pl-0"
											>
												<div class="self-center h-4">
													{{ iG.incentives_gift_gift_quantity }} unit
												</div>
											</div>
											<div
												class="inline-flex items-baseline px-3 text-sm leading-none text-gray-400 font-normal first:pl-0"
											>
												<Coin
													class="mr-1"
													w_class="w-[auto] self-center"
													h_class="h-4"
												/>
												<div class="self-center">
													{{ iG.incentives_gift_total_amount }}
												</div>
											</div>
										</div>
									</div>
									<svg
										xmlns="http://www.w3.org/2000/svg"
										class="w-5 h-5 shrink-0"
										width="24"
										height="24"
										viewBox="0 0 24 24"
										stroke-width="2"
										stroke="currentColor"
										fill="none"
										stroke-linecap="round"
										stroke-linejoin="round"
									>
										<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
										<path d="M9 6l6 6l-6 6"></path>
									</svg>
								</div>
							</Link>
						</div>
					</div>
				</div>

				<div class="w-full m-4 lg:m-0">
					<h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">
						Quick access
					</h5>

					<div class="items-center justify-start my-8 flex gap-4 w-full">
						<Link
							:href="route('profile.edit')"
							class="sm:w-auto bg-gray-800 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 text-white rounded-lg inline-flex items-center justify-center px-4 py-2.5 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700"
						>
							<div class="text-left rtl:text-right">
								<div class="mb-1 text-xs">Edit your info</div>
								<div class="-mt-1 font-sans text-sm font-semibold">Profile</div>
							</div>
						</Link>
						<Link
							v-if="isLoggedInUserAnAttorney"
							:href="route('profile.edit')"
							class="sm:w-auto bg-gray-800 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 text-white rounded-lg inline-flex items-center justify-center px-4 py-2.5 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700"
						>
							<div class="text-left rtl:text-right">
								<div class="mb-1 text-xs">Expand the firm</div>
								<div class="-mt-1 font-sans text-sm font-semibold">
									Manage positions
								</div>
							</div>
						</Link>
					</div>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
