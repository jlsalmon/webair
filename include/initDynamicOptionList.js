var dest = new DynamicOptionList("origin","destination");
            dest.setFormName("booking");

            dest.forValue("Bristol").addOptions("Dublin","Glasgow","Manchester","Newcastle");
            dest.forValue("Dublin").addOptions("Glasgow");
            dest.forValue("Glasgow").addOptions("Bristol","Newcastle");
            dest.forValue("Manchester").addOptions("Bristol");
            dest.forValue("Newcastle").addOptions("Bristol","Manchester");
            dest.selectFirstOption = false;