<div class="container-card">
    @foreach($fleets as $fleet)
        <div class="card">
            <div class="card-header">
                <img src="https://c0.wallpaperflare.com/preview/483/210/436/car-green-4x4-jeep.jpg" alt="rover" />
            </div>
            <div class="card-body">
                <span class="tag tag-purple">Forfait</span>
                <h4>
                    Forfait location : {{ $fleet->name }}
                </h4>
                <p>
                    An exploration into the truck's polarising design
                </p>
                <div class="user">
                    <select>
                        <option>1 heures</option>
                        <option>3 heures</option>
                        <option>10 heures</option>
                    </select>
                    <button type="button" class="tag tag-purple">
                        <i class="fa fa-cart-plus"></i> Acheter
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>
<style>
    @import url("https://fonts.googleapis.com/css2?family=Roboto&display=swap");
    * {
        box-sizing: border-box;
    }
    .container-card {
        display: flex;
        width: 1040px;
        justify-content: space-evenly;
        flex-wrap: wrap;
    }
    .container-card .card {
        margin: 10px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        width: 300px;
    }
    .container-card .card-header img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .container-card .card-body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        padding: 20px;
        min-height: 250px;
    }

    .tag {
        background: #cccccc;
        border-radius: 50px;
        font-size: 12px;
        margin: 0;
        color: #fff;
        padding: 2px 10px;
        text-transform: uppercase;
        cursor: pointer;
    }
    .tag-teal {
        background-color: #47bcd4;
    }
    .tag-purple {
        background-color: #5e76bf;
    }
    .tag-pink {
        background-color: #cd5b9f;
    }

    .card-body p {
        font-size: 13px;
        margin: 0 0 40px;
    }
    .user {
        display: flex;
        margin-top: auto;
    }

    .user img {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        margin-right: 10px;
    }
    .user-info h5 {
        margin: 0;
    }
    .user-info small {
        color: #545d7a;
    }
</style>
