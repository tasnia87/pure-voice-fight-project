import{h}from"@stencil/core";import Fragment from"stencil-fragment";export class PrestoPlayerButton{constructor(){this.hasFocus=!1,this.hasLabel=!1,this.hasPrefix=!1,this.hasSuffix=!1,this.type="default",this.size="medium",this.full=!1,this.disabled=!1,this.submit=!1,this.name=void 0,this.value=void 0,this.href=void 0,this.target=void 0,this.download=void 0}componentWillLoad(){this.handleSlotChange()}click(){this.button.click()}focus(t){this.button.focus(t)}blur(){this.button.blur()}handleSlotChange(){this.hasLabel=!!this.button.children,this.hasPrefix=!!this.button.querySelector('[slot="prefix"]'),this.hasSuffix=!!this.button.querySelector('[slot="suffix"]')}handleBlur(){this.hasFocus=!1,this.prestoBlur.emit()}handleFocus(){this.hasFocus=!0,this.prestoFocus.emit()}handleClick(t){this.disabled&&(t.preventDefault(),t.stopPropagation())}render(){const t=!!this.href,e=h(Fragment,null,h("span",{part:"prefix",class:"button__prefix"},h("slot",{onSlotchange:()=>this.handleSlotChange(),name:"prefix"})),h("span",{part:"label",class:"button__label"},h("slot",{onSlotchange:()=>this.handleSlotChange()})),h("span",{part:"suffix",class:"button__suffix"},h("slot",{onSlotchange:()=>this.handleSlotChange(),name:"suffix"}))),s=h("button",{part:"base",class:{button:!0,"button--default":"default"===this.type,"button--primary":"primary"===this.type,"button--success":"success"===this.type,"button--info":"info"===this.type,"button--warning":"warning"===this.type,"button--danger":"danger"===this.type,"button--text":"text"===this.type,"button--small":"small"===this.size,"button--medium":"medium"===this.size,"button--large":"large"===this.size,"button--disabled":this.disabled,"button--focused":this.hasFocus,"button--has-label":this.hasLabel,"button--has-prefix":this.hasPrefix,"button--has-suffix":this.hasSuffix},disabled:this.disabled,type:this.submit?"submit":"button",name:this.name,value:this.value,onBlur:()=>this.handleBlur,onFocus:()=>this.handleFocus,onClick:()=>this.handleClick},e),i=h("a",{part:"base",class:{button:!0,"button--default":"default"===this.type,"button--primary":"primary"===this.type,"button--success":"success"===this.type,"button--info":"info"===this.type,"button--warning":"warning"===this.type,"button--danger":"danger"===this.type,"button--text":"text"===this.type,"button--small":"small"===this.size,"button--medium":"medium"===this.size,"button--large":"large"===this.size,"button--disabled":this.disabled,"button--focused":this.hasFocus,"button--has-label":this.hasLabel,"button--has-prefix":this.hasPrefix,"button--has-suffix":this.hasSuffix},href:this.href,target:this.target,download:this.download,rel:this.target?"noreferrer noopener":void 0,role:"button","aria-disabled":this.disabled?"true":"false",tabindex:this.disabled?"-1":"0",onBlur:()=>this.handleBlur,onFocus:()=>this.handleFocus,onClick:()=>this.handleClick},e);return t?i:s}static get is(){return"presto-player-button"}static get encapsulation(){return"shadow"}static get originalStyleUrls(){return{$:["presto-player-button.scss"]}}static get styleUrls(){return{$:["presto-player-button.css"]}}static get properties(){return{type:{type:"string",mutable:!1,complexType:{original:"'default' | 'primary' | 'success' | 'info' | 'warning' | 'danger' | 'text'",resolved:'"danger" | "default" | "info" | "primary" | "success" | "text" | "warning"',references:{}},required:!1,optional:!1,docs:{tags:[],text:"The button's type."},attribute:"type",reflect:!0,defaultValue:"'default'"},size:{type:"string",mutable:!1,complexType:{original:"'small' | 'medium' | 'large'",resolved:'"large" | "medium" | "small"',references:{}},required:!1,optional:!1,docs:{tags:[],text:"The button's size."},attribute:"size",reflect:!0,defaultValue:"'medium'"},full:{type:"boolean",mutable:!1,complexType:{original:"boolean",resolved:"boolean",references:{}},required:!1,optional:!0,docs:{tags:[],text:"Draws the button with a caret for use with dropdowns, popovers, etc."},attribute:"full",reflect:!0,defaultValue:"false"},disabled:{type:"boolean",mutable:!1,complexType:{original:"boolean",resolved:"boolean",references:{}},required:!1,optional:!0,docs:{tags:[],text:"Disables the button."},attribute:"disabled",reflect:!0,defaultValue:"false"},submit:{type:"boolean",mutable:!1,complexType:{original:"boolean",resolved:"boolean",references:{}},required:!1,optional:!0,docs:{tags:[],text:"Indicates if activating the button should submit the form. Ignored when `href` is set."},attribute:"submit",reflect:!0,defaultValue:"false"},name:{type:"string",mutable:!1,complexType:{original:"string",resolved:"string",references:{}},required:!1,optional:!1,docs:{tags:[],text:"An optional name for the button. Ignored when `href` is set."},attribute:"name",reflect:!1},value:{type:"string",mutable:!1,complexType:{original:"string",resolved:"string",references:{}},required:!1,optional:!1,docs:{tags:[],text:"An optional value for the button. Ignored when `href` is set."},attribute:"value",reflect:!1},href:{type:"string",mutable:!1,complexType:{original:"string",resolved:"string",references:{}},required:!1,optional:!1,docs:{tags:[],text:"When set, the underlying button will be rendered as an `<a>` with this `href` instead of a `<button>`."},attribute:"href",reflect:!1},target:{type:"string",mutable:!1,complexType:{original:"'_blank' | '_parent' | '_self' | '_top'",resolved:'"_blank" | "_parent" | "_self" | "_top"',references:{}},required:!1,optional:!1,docs:{tags:[],text:"Tells the browser where to open the link. Only used when `href` is set."},attribute:"target",reflect:!0},download:{type:"string",mutable:!1,complexType:{original:"string",resolved:"string",references:{}},required:!1,optional:!1,docs:{tags:[],text:"Tells the browser to download the linked file as this filename. Only used when `href` is set."},attribute:"download",reflect:!1}}}static get states(){return{hasFocus:{},hasLabel:{},hasPrefix:{},hasSuffix:{}}}static get events(){return[{method:"prestoBlur",name:"prestoBlur",bubbles:!0,cancelable:!0,composed:!0,docs:{tags:[],text:"Emitted when the button loses focus."},complexType:{original:"void",resolved:"void",references:{}}},{method:"prestoFocus",name:"prestoFocus",bubbles:!0,cancelable:!0,composed:!0,docs:{tags:[],text:"Emitted when the button gains focus."},complexType:{original:"void",resolved:"void",references:{}}}]}static get elementRef(){return"button"}}