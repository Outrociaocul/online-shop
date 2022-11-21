function changeForm()
        {
            let productType = document.getElementById("productType").value;
            
            let newProperties = document.createElement('div'); 
            newProperties.id = "switchedDiv";

	    let form = document.getElementById('product_form');

            switch (productType)
            {
            case 'choose':
                break; 
            case 'DVD':
                newProperties.innerHTML = ' \
                <label for="size">Size (MB)</label> \
                <input type="text" id="size" name="size" placeholder="Please provide size in MB"> \
                <div id="rsp_size"> \
		<input type="checkbox" class="secret" style="display:none;" value="false"> \
				    <!-- --></div>';
                break;
            case 'Furniture':
                newProperties.innerHTML = ' \
                <label for="height">Height (CM)</label> \
                <input type="text" id="height" name="height" placeholder="Please provide height in CM"> \
                <div id="rsp_height"> \
		<input type="checkbox" class="secret" style="display:none;" value="false"> \
				    <!-- --></div><br> \
                <label for="width">Width (CM)</label> \
                <input type="text" id="width" name="width" placeholder="Please provide width in CM"> \
                <div id="rsp_width"> \
		<input type="checkbox" class="secret" style="display:none;" value="false"> \
				    <!-- --></div><br> \
                <label for="length">Length (CM)</label> \
                <input type="text" id="length" name="length" placeholder="Please provide length in CM"> \
                <div id="rsp_length"> \
		<input type="checkbox" class="secret" style="display:none;" value="false"> \
				    <!-- --></div>';
                break;
            case 'Book':
                newProperties.innerHTML = ' \
                <label for="size">Weight (KG)</label> \
                <input type="text" id="weight" name="weight" placeholder="Please provide weight in KG"> \
                <div id="rsp_weight"> \
		<input type="checkbox" class="secret" style="display:none;" value="false"> \
				    <!-- --></div>';
                break;
            }

            let switchedDiv = document.getElementById("switchedDiv");
            if (switchedDiv !== null)
            {
                switchedDiv.replaceWith(newProperties);
            }
            else 
            {
                document.getElementById("inputs").append(newProperties);
            }

		switch(productType)
		{
			case 'choose':
				break; 
			case 'DVD':
				form.size.addEventListener("change", function(e)
					{
						if (this.value!= "")
						{
							callAjax("checkSize", this.value, this.id);
						}
					}, false);
				break;
			case 'Furniture':
				form.width.addEventListener("change", function(e)
					{
						if (this.value!= "")
						{
							callAjax("checkWidth", this.value, this.id);
						}
					}, false);
				form.height.addEventListener("change", function(e)
					{
						if (this.value!= "")
						{
							callAjax("checkHeight", this.value, this.id);
						}
					}, false);
				form.length.addEventListener("change", function(e)
					{
						if (this.value!= "")
						{
							callAjax("checkLength", this.value, this.id);
						}
					}, false);
				break; 
			case 'Book':
				form.weight.addEventListener("change", function(e)
					{
						if (this.value!= "")
						{
							callAjax("checkWeight", this.value, this.id);
						}
					}, false);
				break;
		}
        }
