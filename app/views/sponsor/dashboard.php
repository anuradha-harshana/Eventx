<div style="max-width: 1200px; margin: 0 auto; padding: 30px 20px;">
    <h1 style="color: #333; margin-bottom: 30px; font-size: 32px;">Sponsor Dashboard</h1>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <!-- Sponsorship Requests Card -->
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px; padding: 30px; color: white; text-align: center; cursor: pointer; transition: transform 0.3s ease; box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);" 
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'"
             onclick="window.location.href='<?= SITE_URL ?>sponRequests'">
            <div style="font-size: 40px; margin-bottom: 15px;">📬</div>
            <h3 style="margin: 0 0 10px 0; font-weight: 600;">Sponsorship Requests</h3>
            <p style="margin: 0; opacity: 0.9; font-size: 14px;">Review sponsorship requests from organizers</p>
        </div>

        <!-- Profile Card -->
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px; padding: 30px; color: white; text-align: center; cursor: pointer; transition: transform 0.3s ease; box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);" 
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'"
             onclick="window.location.href='<?= SITE_URL ?>sponProf'">
            <div style="font-size: 40px; margin-bottom: 15px;">👤</div>
            <h3 style="margin: 0 0 10px 0; font-weight: 600;">Profile</h3>
            <p style="margin: 0; opacity: 0.9; font-size: 14px;">Edit your brand information</p>
        </div>

        <!-- Analytics Card -->
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px; padding: 30px; color: white; text-align: center; cursor: pointer; transition: transform 0.3s ease; box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);" 
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'"
             onclick="window.location.href='<?= SITE_URL ?>sponAna'">
            <div style="font-size: 40px; margin-bottom: 15px;">📊</div>
            <h3 style="margin: 0 0 10px 0; font-weight: 600;">Analytics</h3>
            <p style="margin: 0; opacity: 0.9; font-size: 14px;">View your sponsorship stats</p>
        </div>

        <!-- Explore Card -->
        <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 12px; padding: 30px; color: white; text-align: center; cursor: pointer; transition: transform 0.3s ease; box-shadow: 0 4px 15px rgba(250, 112, 154, 0.3);" 
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'"
             onclick="window.location.href='<?= SITE_URL ?>explore'">
            <div style="font-size: 40px; margin-bottom: 15px;">🔍</div>
            <h3 style="margin: 0 0 10px 0; font-weight: 600;">Explore</h3>
            <p style="margin: 0; opacity: 0.9; font-size: 14px;">Find events to sponsor</p>
        </div>
    </div>
</div>