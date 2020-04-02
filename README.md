# laravelCodeEngine
Laravel Project starter for laravel developer

# Using
INSPINIA - Responsive Admin Theme

# Instructions 
# Clone project
# Create database and config by .env
# Create migration for your table like 

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->tinyInteger('units_id')->autoIncrement('true');
            $table->string('units_name', 100);
            $table->string('type', 100);
            $table->string('symbol', 20);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->bigInteger('created_by')->foreignId('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('units');
    }
    
        /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigInteger('items_id')->autoIncrement('true');
            $table->string('items_name',200)->nullable('false');
            $table->tinyInteger('units_id');
            $table->foreign('units_id')->references('units_id')->on('units')->onDelete('cascade')->onUpdate('cascade');
            $table->text('description')->default(NULL);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->bigInteger('created_by')->foreignId('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }

# php artisan migrate
# php artisan make:module ( table name  )
# php artisan serve

# run http://127.0.0.1:8000/grid/units


