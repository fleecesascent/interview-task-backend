# Recruitment Task

### Comments

* PHP version - writing my implementation, I stuck to the version pointed in composer.json - 8.0 - even if the containers use 8.2

Tests can be run by:

```shell
docker compose exec workspace php artisan test
```

Added endpoints:
* GET http://localhost/api/invoices - list of invoices. Note: the `invoices` table contains only one relation with 
the `companies` one which i have used to fulfill the "company" field in the invoice data. Because i wasn't sure
how to fulfill the "billedCompany" field i left it empty.

```json
{
  "invoiceNumber": "e30e68a1-9952-336a-97e0-19d369dd6ea1",
  "invoiceDate": "2017-09-05",
  "dueDate": "1972-08-30",
  "company": {
    "name": "Terry, Nitzsche and Berge",
    "streetAddress": "350 Jaskolski Terrace Suite 652",
    "city": "Homenickton",
    "zipCode": "17040",
    "phone": "(678) 752-4733",
    "email": "weber.alvina@example.org"
  },
  "billedCompany": "",
  "products": [
      {
        "name": "water",
        "quantity": 82,
        "unitPrice": 2089830,
        "total": 171366060
      }
  ],
"totalPrice": 171366060
}
```

* PATCH http://localhost/api/invoices/{INVOICE_UUID}/approve - endpoint to approve an invoice.
  * If the invoice is already approved/rejected or doesn't exist or uuid is incorrect, the response will be 422 - Unprocessable Entity
  * When the status is changed, the response will be 204

* PATCH http://localhost/api/invoices/{INVOICE_UUID}/reject - endpoint to reject an invoice.
  * If the invoice is already approved/rejected or doesn't exist or uuid is incorrect, the response will be 422 - Unprocessable Entity
  * When the status is changed, the response will be 204

There is only one error code used to simplify the error handling. The detailed error message is returned in the response body.

I tried to make the module's directory structure similar to the already present "Approval" module. 
However, I'm not exactly sure what "proper DDD structure" means here - I hope my proposition is close to what you expected.


### Invoice module with approve and reject system as a part of a bigger enterprise system. Approval module exists and you should use it. It is Backend task, no Frontend is needed.
---
Please create your own repository and make it public or invite us to check it.


<table>
<tr>
<td>

- Invoice contains:
  - Invoice number
  - Invoice date
  - Due date
  - Company
    - Name 
    - Street Address
    - City
    - Zip code
    - Phone
  - Billed company
    - Name 
    - Street Address
    - City
    - Zip code
    - Phone
    - Email address
  - Products
    - Name
    - Quantity
    - Unit Price	
    - Total
  - Total price
</td>
<td>
Image just for visualization
<img src="https://templates.invoicehome.com/invoice-template-us-classic-white-750px.png" style="width: auto"; height:100%" />
</td>
</tr>
</table>

### TO DO:
Simple Invoice module which is approving or rejecting single invoice using information from existing approval module which tells if the given resource is approvable / rejectable. Only 3 endpoints are required:
```
  - Show Invoice data, like in the list above
  - Approve Invoice
  - Reject Invoice
```
* In this task you must save only invoices so don’t write repositories for every model/ entity.

* You should be able to approve or reject each invoice just once (if invoice is approved you cannot reject it and vice versa.

* You can assume that product quantity is integer and only currency is USD.

* Proper seeder is located in Invoice module and it’s named DatabaseSeeder

* In .env.example proper connection to database is established.

* Using proper DDD structure is mandatory (with elements like entity, value object, repository, mapper / proxy, DTO).
Unit tests in plus.

* Docker is in docker catalog and you need only do 
  ```
  ./start.sh
  ``` 
  to make everything work

  docker container is in docker folder. To connect with it just:
  ```
  docker compose exec workspace bash
  ``` 
