<template id="tmpl-assely-fields">
	<div class="pure-g">
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
	</div>
</template>
