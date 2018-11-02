// panel.plugin("currency-field",{
//   fields: {
//     "currency" =>  {
//       props: {
//         message: String
//       },
//       template: '<p>{{ message }}</p>'
//     } 
//   }
// });

// panel.plugin("currency-field",{
//   fields: {
//     "currency" =>  {
//       template: `
//         <kirby-field v-bind="$props" prefix="€">
//           <input type="number" :value.number="state" @input="input($event.target.value)">
//           <kirby-dropdown slot="icon">
//             <kirby-button tabindex="-1" icon="angle-down" @click="$refs.dropdown.toggle()" />
//             <kirby-dropdown-content ref="dropdown" align="right">
//               <kirby-dropdown-item icon="add" @click="input(15.00)">€ 15.00</kirby-dropdown-item>
//               <kirby-dropdown-item icon="add" @click="input(30.00)">€ 30.00</kirby-dropdown-item>
//               <kirby-dropdown-item icon="add" @click="input(45.00)">€ 45.00</kirby-dropdown-item>
//             </kirby-dropdown-content>
//           </kirby-dropdown>
//         </kirby-field>
//       `
//     } 
//   }
// });