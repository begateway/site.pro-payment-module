ElementRegister.registerPaymentGateway({
	name: 'BePaid',
	id: 'bepaid',
	pageUrl: 'https://begateway.com/',
	keyFieldId: 'shop_id',
	keyField2Id: 'shop_key',
	keyField3Id: 'demo',
	keyFieldDef: {type: 'HorizontalLayout', noPadding: true, columnWeights: [6,6,12], children: [
		{type: 'TextField', placeholder: __('Shop ID'), id: 'key'},
		{type: 'TextField', placeholder: __('Shop key'), id: 'key2'},
		{type: 'CheckBox', label: __('Test mode'), id: 'key3', css: {padding: 7, marginTop: 5, display: 'inline-block'}, init: function() {
			this.getElem().attr('title', __('For testing purpose without real payments')).tooltip({placement: 'right'});
		}}
	]},
	titleFieldId: 'label',
	nameFieldId: 'ordername,orderdescription',
	priceFieldId: 'sum',
	globalVars: ['shop_id', 'shop_key']
});

PluginWrapper.registerPlugin('bepaid', {
	name: 'bePaid',
	element: {
    minSize: {width: 162, height: 56},
    defaultSize: {width: 162, height: 56},
    resizable: false
	},
	propertyDialog: {
		noScroll: true,
		tabs: [
			{children: [
        {type: 'HorizontalLayout', spacing: 15, children: [
  				{type: 'VerticalLayout', children: [
  					{type: 'Label', text: __('Shop ID'), helpText:__("Shop ID in your payment provider")},
  					{type: 'TextField', id: 'shop_id'}
  				]},
  				{type: 'VerticalLayout', children: [
  					{type: 'Label', text: __('Shop key'), helpText:__("Shop secret key issued by your payment provider")},
  					{type: 'TextField', id: 'shop_key'}
  				]}
        ]},
        {type: 'HorizontalLayout', columnWeights: [6, 3, 3], columnWeightsSmall: [6, 3, 3], css: {marginTop: 15}, children: [
  				{type: 'VerticalLayout', children: [
  					{type: 'Label', text: __('Item name'), helpText:__('The name of the goods. Cannot contain special symbols')},
  					{type: 'TextField', id: 'orderdescription'}
  				]},
					{type: 'VerticalLayout', children: [
						{type: 'Label', text: __('Amount'), helpText: __("Amount to be transferred")},
						{type: 'TextField', id: 'sum'}
					]},
          {type: 'VerticalLayout', children: [
            {type: 'Label', text: __('Currency')},
            {type: 'DropdownBox', id: 'currency', options: [
                {id: '#RUB', name: 'RUB', value: 'RUB'},
                {id: '#USD', name: 'USD', value: 'USD'},
                {id: '#EUR', name: 'EUR', value: 'EUR'},
                {id: '#BYN', name: 'BYN', value: 'BYN'},
                {id: '#AMD', name: 'AMD', value: 'AMD'},
                {id: '#AUD', name: 'AUD', value: 'AUD'},
                {id: '#AZN', name: 'AZN', value: 'AZN'},
                {id: '#BGN', name: 'BGN', value: 'BGN'},
                {id: '#BRL', name: 'BRL', value: 'BRL'},
                {id: '#CAD', name: 'CAD', value: 'CAD'},
                {id: '#CHF', name: 'CHF', value: 'CHF'},
                {id: '#CNY', name: 'CNY', value: 'CNY'},
                {id: '#CZK', name: 'CZK', value: 'CZK'},
                {id: '#DKK', name: 'DKK', value: 'DKK'},
                {id: '#GBP', name: 'GBP', value: 'GBP'},
                {id: '#HUF', name: 'HUF', value: 'HUF'},
                {id: '#INR', name: 'INR', value: 'INR'},
                {id: '#JPY', name: 'JPY', value: 'JPY'},
                {id: '#KGS', name: 'KGS', value: 'KGS'},
                {id: '#KRW', name: 'KRW', value: 'KRW'},
                {id: '#KZT', name: 'KZT', value: 'KZT'},
                {id: '#MDL', name: 'MDL', value: 'MDL'},
                {id: '#NOK', name: 'NOK', value: 'NOK'},
                {id: '#PLN', name: 'PLN', value: 'PLN'},
                {id: '#RON', name: 'RON', value: 'RON'},
                {id: '#SEK', name: 'SEK', value: 'SEK'},
                {id: '#SGD', name: 'SGD', value: 'SGD'},
                {id: '#TJS', name: 'TJS', value: 'TJS'},
                {id: '#TMT', name: 'TMT', value: 'TMT'},
                {id: '#TRY', name: 'TRY', value: 'TRY'},
                {id: '#UAH', name: 'UAH', value: 'UAH'},
                {id: '#UZS', name: 'UZS', value: 'UZS'},
                {id: '#ZAR', name: 'ZAR', value: 'ZAR'}
              ]}
            ]}
				]},
				{type: 'VerticalLayout', css: {marginTop: 15}, children: [
					{type: 'Label', text: __('Button label')},
					{type: 'TextField', id: 'button_label'}
				]},
				{type: 'HorizontalLayout', css: {marginTop: 15}, children: [
					{type: 'VerticalLayout', children: [
						{type: 'Label', text: __('Border')},
						{type: 'BorderSelector', id: 'button_border'}
					]},
					{type: 'VerticalLayout', children: [
						{type: 'Label', text: __('Background')},
						{type: 'ColorSelector', id: 'button_color'}
					]},
					{type: 'VerticalLayout', children: [
						{type: 'Label', text: __('Color')},
						{type: 'ColorSelector', id: 'label_color', noTransparent: true}
					]},
				]},
				{type: 'HorizontalLayout', css: {marginTop: 15}, children: [
          {type: 'VerticalLayout', children: [
            {type: 'Label', text: __('Font')},
            {type: 'FontFamilySelector', id: 'font_family', noFixedWidth: true}
          ]},
          {type: 'VerticalLayout', children: [
            {type: 'Label', text: __('Size')},
            {type: 'SizeSelector', id: 'font_size'}
          ]},
				]},
				{type: 'VerticalLayout', css: {marginTop: 15}, children: [
					{type: 'CheckBox', id: 'demo', css: {display: 'inline-block'}, label: __('Test mode'), init: function() {
						this.getElem().attr('title', __('For testing purpose without real payments'));
						this.getElem().tooltip({placement: 'bottom'});
					}}
				]}
			]}
		]
	},
  resizeAction: function (data, elem) {
      if (!this.resizeTimeout) {
          var self = this;
          this.resizeTimeout = setTimeout(function () {
              self.resizeTimeout = null;
              self.updateElement();
          }, 1000);
      }
  },
	openAction: function(fields, data, elem) {
		fields.shop_id.setText(data.content.shop_id);
		fields.shop_key.setText(data.content.shop_key);
		fields.orderdescription.setText(data.content.orderdescription);
		fields.sum.setText(data.content.sum);

    var itm;
    itm = fields.currency.getItemById('#' + data.content.currency);
    fields.currency.selectItem(itm);

		fields.button_label.setText(data.content.button_label);
		fields.button_color.setValue(data.content.button_color);
    fields.font_family.setValue(data.content.font_family);
    fields.font_size.setValue(data.content.font_size);
		fields.label_color.setValue(data.content.label_color);
		fields.button_border.setValue(data.content.button_border);
		fields.demo.setValue(data.content.demo);
	},
	applyAction: function(fields, data, elem) {
		data.content.shop_id = fields.shop_id.getText();
		data.content.shop_key = fields.shop_key.getText();
		data.content.orderdescription = fields.orderdescription.getText();
		data.content.sum = fields.sum.getText();
    var itm;
    itm = fields.currency.getSelectedItem();
    data.content.currency = itm.getOriginal().value;

		data.content.button_label = fields.button_label.getText();
		data.content.button_color = fields.button_color.getValue();
    data.content.font_family = fields.font_family.getValue();
    data.content.font_size = fields.font_size.getValue();
		data.content.label_color = fields.label_color.getValue();
		data.content.button_border = fields.button_border.getValue();
		data.content.demo = fields.demo.getValue();
		data.content.buttonBorderCss = this.updateBorderCss(data);
	},
	updateBorderCss: function(data) {
		var borderCss = '';
		var border = data.content.button_border;
		if (border.css !== undefined) {
			if (typeof border.css === 'string') {
				borderCss = border.css;
			} else {
				for (var i in border.css) {
					borderCss += i + ': ' + border.css[i] + ';';
				}
			}
		} else {
			borderCss = 'border: ' + border.weight + 'px ' + border.style + ' ' + border.color + ';';
		}
		return borderCss;
	},
	loadAction: function (data) {
		if (!data.content.shop_id) data.content.shop_id = '4225';
		if (!data.content.shop_key) data.content.shop_key = '3834fbef1fe6ea024ef77f5c79ec7ff1ba710ea6241c08c2f341afda8af4c1c4';
		if (!data.content.ordername) data.content.orderdescription = __('Demo payment');
		if (!data.content.sum) data.content.sum = '1';
    if (!data.content.currency) data.content.currency = 'BYN';

		if (!data.content.button_label) data.content.button_label = __('Proceed to payment');
		if (!data.content.button_color) data.content.button_color = '#e37125';
    if (!data.content.font_family) data.content.font_family = 'Arial';
    if (!data.content.font_size) data.content.font_size = '12';
    if (!data.content.label_color) data.content.label_color = '#fff';
		if (!data.content.button_border) data.content.button_border = {
			color: '#ccc',
			style: 'none',
			weight: 1,
			css: { border: '1px none #ccc' }
		};
		if (data.content.demo === undefined) data.content.demo = false;
		data.content.buttonBorderCss = this.updateBorderCss(data);
	}
});
