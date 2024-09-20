# Delivery Management System

This is a Laravel-based Delivery Management System that assigns delivery boys to orders based on their availability and capacity. It uses the Repository Pattern to separate concerns for database operations and enhance code maintainability.

## Features

- **Delivery Boy Assignment**: Assigns orders to delivery boys while respecting their capacity.
- **Capacity Management**: Each delivery boy can handle a specific number of orders simultaneously, and no new order will be assigned to them within 30 minutes unless they have capacity.
- **Repository Pattern**: Provides cleaner, maintainable code by separating business logic from database operations.
- **Order Management**: Automatically assigns available delivery boys to orders, and prevents assigning duplicate or invalid orders.
- **Timestamps**: Tracks when orders are assigned and prevents order assignments after capacity is reached or within the 30-minute delivery window.

## Installation Instructions

### Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL or compatible database
- Laravel 11 

### Step 1: Clone the Repository

```bash
git clone https://github.com/ksubodh9/order-management-system.git
cd order-management-system
```

### Step 2: Install Dependencies

```bash
composer install
```

### Step 3: Environment Setup

Create a copy of the `.env.example` file:

```bash
cp .env.example .env
```

Set up your database credentials in the `.env` file:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 4: Generate Application Key

```bash
php artisan key:generate
```

### Step 5: Run Migrations and Seeders

To create the necessary tables, run the migrations:

```bash
php artisan migrate
```

To seed the database with sample data:

```bash
php artisan db:seed
```

This will create delivery boys and orders in the database for testing purposes.

### Step 6: Running the Project

After setting up everything, run the Laravel development server:

```bash
php artisan serve
```

Visit `http://127.0.0.1:8000` to access the application.

## Usage

### Delivery Boy Assignment Logic

The system automatically assigns orders to delivery boys according to the following logic:

1. Each delivery boy has a capacity limit for how many orders they can handle simultaneously.
2. No new order will be assigned if a delivery boy is at capacity or within the last 30 minutes of having accepted an order.
3. The system checks which delivery boy is available and assigns the order accordingly.

### Example Commands using Tinker

You can use **Tinker** to interact with the application from the command line:

1. **Start Tinker**:

   ```bash
   php artisan tinker
   ```

2. **Assign an Order to a Delivery Boy**:

   Use the repository to assign an order:

   ```php
   $repository = app(\App\Repositories\Eloquent\DeliveryRepository::class);

   // Assign order ID 10 to an available delivery boy
   $repository->assignOrderToDeliveryBoy(10);
   ```

   If the order is successfully assigned, you'll receive the delivery boy's name like this:

   ```bash
   =>
   [
       "status" => true,
       "message" => "This order has been successfully assigned to Delivery Boy A",
   ]
   ```

   If no delivery boy is available or an error occurs, an exception will be thrown.

3. **Check Delivery Boy Availability**:

   To check if a specific delivery boy is free to take an order, you can use the following command:

   ```php
   $repository->isDeliveryBoyFree(1); // Check if delivery boy with ID 1 is available
   ```

   This will return 
   ```bash
   =>
    [                                               
        "status" => true,                             
        "message" => "The delivery boy is available.",
    ]  
   ```                                             
    if the delivery boy is available or.

   ```bash
   =>
    [                                               
        "status" => true,                             
        "message" => "The delivery boy is not available.",
    ]  
   ``` 

   if they are busy or have reached their capacity.

4. **Get Available Delivery Boy**:

   You can retrieve the next available delivery boy with this command:

   ```php
   $availableBoy = $repository->getAvailableDeliveryBoy();
   ```

   If there is an available delivery boy, their details will be returned. If not, you'll get 
   
   ```bash
   =>
    [                                               
        "status" => true,                             
        "message" => "There are no available delivery boys. Please try after some time.",
    ]  
   ``` 
   .

### Example of Handling Errors

Here’s how to handle potential errors when running Tinker commands:

- **Invalid Order ID**:

   If you try to assign an invalid order ID (i.e., an order that does not exist in the database), it will throw an exception:

   ```php
   try {
       $repository->assignOrderToDeliveryBoy(999); // Invalid order ID
   } catch (\Exception $e) {
       echo $e->getMessage(); // Outputs: "No order ID provided or Invalid order ID."
   }
   ```

- **Order Already Assigned**:

   If you attempt to assign the same order to a delivery boy again, it will prevent duplication:

   ```php
   try {
       $repository->assignOrderToDeliveryBoy(10); // Already assigned order ID
   } catch (\Exception $e) {
       echo $e->getMessage(); // Outputs: "This order has already been assigned to the delivery boy."
   }
   ```

## Project Structure

This project uses the Laravel Repository Pattern. Key components are:

- **Repositories**: All database operations are handled through repositories (`App\Repositories\Eloquent\DeliveryRepository`).
- **Models**: Delivery boys and orders are managed via Eloquent models (`App\Models\DeliveryBoy`, `App\Models\Order`).
- **Migrations**: Database schema is defined in migration files.

### Migrations

- `orders`: Stores order details.
- `delivery_boys`: Stores delivery boy information.
- `delivery_boy_order`: Pivot table to assign orders to delivery boys.

### Seeder

- Seeds delivery boys and orders for testing.

### Pivot Table (delivery_boy_order)

This pivot table connects delivery boys and orders, keeping track of assignments with timestamps.

## Common Errors & Troubleshooting

- **Foreign Key Constraint Violation**: Ensure you’re using valid order and delivery boy IDs when assigning orders. Check for existing records in the `orders` and `delivery_boys` tables.
- **Ambiguous Column Error**: The project explicitly references column names to avoid ambiguity during join operations, but if you still encounter issues, verify your table schemas.

## License

This project is open-source and free to use under the [MIT License](LICENSE).
