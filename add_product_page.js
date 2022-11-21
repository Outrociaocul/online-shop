
        let form = document.getElementById('product_form');

		function saveProduct()
		{
			let req = new XMLHttpRequest();
			req.open('POST', '/save_product.php', false);
			req.onreadystatechange = function () {
				if (req.readyState != 4) return; 

				if (req.responseText == "OK")
				{
					window.location.pathname = "/";
				}
				else 
				{
					console.log(req.responseText);
					alert("Please fill all fields");
					return; 
				}
			};

			let sku = document.getElementById("sku").value; 
			let name = document.getElementById("name").value; 
			let price = document.getElementById("price").value;

			let json = null; 

			switch (document.getElementById("productType").selectedIndex)
			{
				case 0:
					alert("Please fill all fields");
					return; 
				case 1:
					json = {
						type: "dvd",
						sku: sku, 
						name: name, 
						price: price, 
						size: document.getElementById("size").value
					};
					break; 
				case 2: 
					json = {
						type: "furniture",
						sku: sku, 
						name: name, 
						price: price, 
						width: document.getElementById("width").value,
						height: document.getElementById("height").value,
						length: document.getElementById("length").value
					};
					break;
				case 3: 
					json = {
						type: "book",
						sku: sku, 
						name: name, 
						price: price, 
						weight: document.getElementById("weight").value
					};
					break; 
			}

			console.log(JSON.stringify(json));

			//return;
			req.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
			req.send(JSON.stringify(json));
		/*	var switcher = document.getElementById("productType");

			var checked = document.getElementsByClassName("secret");
			for (let i = 0; i < checked.length; i++)
			{
				if (checked.item(i).value == "false")
				{
					alert("Please fill all fields");
					return; 
				}
			}

			if (switcher.selectedIndex == 0)
			{
				alert("Please fill all fields");
				return; 
			}*/
            // form.method = 'POST'; 
            //form.action = '/save_product.php';
            //form.submit(); 
            //console.log("OK");
            //window.location.pathname = "/";
		}

        var callAjax = function(method, value, target) {
            var params = {
                method: method,
                value: value,
                target: target,
            };
            return (new AjaxRequestXML()).post("/ajax-validate.xml.php", params);
        };

        form.addEventListener("submit", saveProduct, false);

        form.sku.addEventListener("change", function(e) {
            if(this.value != "") {
                callAjax("checkSKU", this.value, this.id);
            }
        }, false);

        form.name.addEventListener("change", function(e) {
            if(this.value != "") {
                callAjax("checkName", this.value, this.id);
            }
        }, false);

        form.price.addEventListener("change", function(e) {
            if(this.value != "") {
                callAjax("checkPrice", this.value, this.id);
            }
        }, false);
