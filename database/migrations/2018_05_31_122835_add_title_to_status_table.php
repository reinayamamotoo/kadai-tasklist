   public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('title');
        });
    }


    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }