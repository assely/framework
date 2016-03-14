<template id="tmpl-assely-fields">

	<div class="pure-u-1" v-if="!fields.length && {{ WP_DEBUG }}">
		<assely-alert>
			<i class="dashicons dashicons-info"></i> Have you forgotten fields? Add some, it is really easy.
		</assely-alert>
	</div>

	<assely-box
		v-if="fields.length"
		v-for="field in fields"
		:title="(field.type === 'repeatable') ? field.plural : field.singular"
		:description="field.arguments.description"
		:type="field.type"
		:column="field.arguments.column"
	>
		<component
			:is="'fielder-' + field.type"
			:field="field"
			:namespace="namespace"
		></component>
	</assely-box>

</template>
