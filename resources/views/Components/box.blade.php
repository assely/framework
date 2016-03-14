<template id="tmpl-assely-box">

	<div class="pure-u-@{{ column }}">
		<main class="assely-box assely-box--@{{ type }}">

			<header class="assely-box__header">
				<strong class="title">@{{ title }}</strong>
			</header>

			<section class="assely-box__content">
				<i class="assely-box__description" v-if="description">@{{ description }}</i>
				<slot></slot>
			</section>

		</main>
	</div>

</template>
